<?php

namespace App\Http\Controllers;

use App\Events\OpenGraphRegenerateEvent;
use App\Http\Requests\FundraisingLinkCreateRequest;
use App\Http\Requests\FundraisingRequest;
use App\Models\Fundraising;
use App\Models\FundraisingDetail;
use App\Models\FundraisingShortCode;
use App\Models\Prize;
use App\Models\UserSetting;
use App\Services\ChartService;
use App\Services\FileService;
use App\Services\FundraisingShortCodeService;
use App\Services\GoogleServiceSheets;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FundraisingController extends Controller
{
    public const string PRIZE_ADDED_TO_FUNDRAISING_URL_CONFIRMATION = 'Ваш приз ":prize" додали до збору :fundraisingUrl. Зайдіть в свій кабінет та підтвердіть чи скасуйте запит. :url';

    public function store(FundraisingRequest $request)
    {
        $this->authorize('create', Fundraising::class);

        $attributes = $request->validated();

        if ((int)$attributes['user_id'] !== $request->user()->getId() && !$request->user()->isSuperAdmin()) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }
        $attributes['is_enabled'] = true;

        [$paypal, $cardPrivat, $cardMono, $attributes] = $this->getDetailParams($attributes);

        $fundraising = Fundraising::create($attributes);
        FundraisingDetail::create([
            'id' => $fundraising->getId(),
            'data' => ['card_mono' => $cardMono, 'card_privat' => $cardPrivat, 'paypal' => $paypal,],
        ]);
        $volunteer = $fundraising->getVolunteer();
        if ($volunteer) {
            OpenGraphRegenerateEvent::dispatch($volunteer->getId(), OpenGraphRegenerateEvent::TYPE_USER);
            OpenGraphRegenerateEvent::dispatch($fundraising->getId(), OpenGraphRegenerateEvent::TYPE_FUNDRAISING);
        }

        if ($volunteer && 1 === $volunteer->getFundraisings()->count()) {
            foreach (UserSetting::getNecessarySettingsForVolunteer() as $setting)
                UserSetting::query()->where('user_id', '=', $volunteer->getId())->where('setting', '=', $setting)->delete();
            }
            $volunteer->sendBotMessage(
                'Вітаю! Ви створили свій перший збір. Для того, щоб ви з\'явилися на сторінці волонтерів, вам мають довіряти інші волонтери.' .
                ' Долучайтеся до чату нашої спільноти волонтерів ' . config('app.volunteer_chat_link') .
                ' Там волонтери можуть задати питання, ділитися контактами продавців, ' .
                'домовляються за спільні збори, допомогають один одному репостами, а також можна попросити ' .
                'оперативно виправити знайдену багу на сайті тощо. Ми чекаємо на вас!'
            );

        return new JsonResponse(['url' => route('fundraising.show', compact('fundraising'))]);
    }

    public function create()
    {
        return view('fundraising.create');
    }

    public function storeAvatar(Request $request): JsonResponse
    {
        $this->authorize('create', Fundraising::class);

        $avatar = app(FileService::class)->createAvatar($request, '/images/banners/');

        return new JsonResponse(['avatar' => $avatar, 'csrf' => $this->getNewCSRFToken()]);
    }

    public function checkKey(Request $request): JsonResponse
    {
        $this->authorize('create', Fundraising::class);

        $status = Fundraising::query()->where('key', '=', $request->get('key', ''))->count() > 0 ? Response::HTTP_CONFLICT : Response::HTTP_OK;

        return new JsonResponse(['csrf' => $this->getNewCSRFToken()], $status);
    }

    public function spreadsheet(Request $request): JsonResponse
    {
        $this->authorize('create', Fundraising::class);
        app(GoogleServiceSheets::class)->getRowCollection($request->get('spreadsheet_id'), 0, GoogleServiceSheets::RANGE_DEFAULT, false);

        return new JsonResponse(['csrf' => $this->getNewCSRFToken()], Response::HTTP_OK);
    }

    public function edit(Fundraising $fundraising)
    {
        if ($fundraising->isForget()) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        $this->authorize('update', $fundraising);

        return view('fundraising.edit', compact('fundraising'));
    }

    public function show(Fundraising $fundraising)
    {
        if ($fundraising->isForget()) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        $this->authorize('view', $fundraising);

        return view('fundraising.show', compact('fundraising'));
    }

    public function preload(Fundraising $fundraising)
    {
        if ($fundraising->isForget()) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        $this->authorize('view', $fundraising);
        $rows = null;
        try {
            $rows = app(GoogleServiceSheets::class)->getRowCollection($fundraising->getSpreadsheetId(), $fundraising->getId());
        } catch (Throwable $throwable) {
            Log::critical($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
        }

        return new JsonResponse([
            'html' => view('fundraising.preload', compact('fundraising', 'rows'))->render(),
        ]);
    }

    public function start(Fundraising $fundraising)
    {
        if ($fundraising->isForget()) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        $this->authorize('update', $fundraising);

        $fundraising->update(['is_enabled' => true]);
        OpenGraphRegenerateEvent::dispatch($fundraising->getId(), OpenGraphRegenerateEvent::TYPE_FUNDRAISING);

        return $this->getRedirectResponse($fundraising);
    }

    public function update(FundraisingRequest $request, Fundraising $fundraising)
    {
        if ($fundraising->isForget()) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        $this->authorize('update', $fundraising);

        $validated = $request->validated();

        [$paypal, $cardPrivat, $cardMono, $validated] = $this->getDetailParams($validated);

        $fundraising->update($validated);
        FundraisingDetail::firstOrNew(['id' => $fundraising->getId()])
            ->setData(['card_mono' => $cardMono, 'card_privat' => $cardPrivat, 'paypal' => $paypal])->save();
        OpenGraphRegenerateEvent::dispatch($fundraising->getId(), OpenGraphRegenerateEvent::TYPE_FUNDRAISING);

        return new JsonResponse(['url' => route('fundraising.show', compact('fundraising'))]);
    }

    /**
     * @param Fundraising $fundraising
     * @return RedirectResponse
     */
    protected function getRedirectResponse(Fundraising $fundraising): RedirectResponse
    {
        return redirect(
            route('fundraising.show', compact('fundraising')),
            Response::HTTP_FOUND
        )->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function stop(Fundraising $fundraising)
    {
        if ($fundraising->isForget()) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        $this->authorize('update', $fundraising);

        $fundraising->update(['is_enabled' => false]);
        OpenGraphRegenerateEvent::dispatch($fundraising->getId(), OpenGraphRegenerateEvent::TYPE_FUNDRAISING);

        return $this->getRedirectResponse($fundraising);
    }

    public function destroy(Fundraising $fundraising)
    {
        $this->authorize('delete', $fundraising);

        $fundraising->delete();

        return response()->json();
    }

    public function addPrize(Fundraising $fundraising, Prize $prize)
    {
        if ($fundraising->isForget()) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        $this->authorize('update', $fundraising);

        $isSelf = $fundraising->getUserId() === $prize->getUserId();
        $prize->setFundraisingId($fundraising->getId())
            ->setAvailableStatus($isSelf ? Prize::STATUS_GRANTED : Prize::STATUS_WAITING)->save();
        if (!$isSelf) {
            $prize->getDonater()->sendBotMessage(
                strtr(self::PRIZE_ADDED_TO_FUNDRAISING_URL_CONFIRMATION, [
                    ':prize' => $prize->getName(),
                    ':fundraisingUrl' => route('fundraising.show', compact('fundraising')),
                    ':url' => route('my'),
                ])
            );
        }

        return new JsonResponse(['url' => route('fundraising.show', compact('fundraising'))]);
    }

    public function delPrize(Fundraising $fundraising, Prize $prize)
    {
        if ($fundraising->isForget()) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        $this->authorize('update', $fundraising);

        $prize->setFundraisingId()->setAvailableStatus(Prize::STATUS_NEW)->save();

        return new JsonResponse(['url' => route('fundraising.show', compact('fundraising'))]);
    }

    public function createShortLink(FundraisingLinkCreateRequest $request, Fundraising $fundraising): JsonResponse
    {
        if ($fundraising->isForget()) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        $this->authorize('create', [FundraisingShortCode::class, $fundraising]);

        $data = $request->validated();
        app(FundraisingShortCodeService::class)
            ->createFundraisingShortCode($fundraising->getId(), $data['code'] ?? '');
        OpenGraphRegenerateEvent::dispatch($fundraising->getId(), OpenGraphRegenerateEvent::TYPE_FUNDRAISING);

        return new JsonResponse(['csrf' => $this->getNewCSRFToken()]);
    }

    public function analytics(Fundraising $fundraising)
    {
        if ($fundraising->isForget()) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        $this->authorize('view', $fundraising);
        $rows = $charts = $charts2 = $charts3 = null;
        try {
            $rows = app(GoogleServiceSheets::class)->getRowCollection($fundraising->getSpreadsheetId(), $fundraising->getId());
            $chartsService = app(ChartService::class);
            $charts = $chartsService->getChartPerDay($rows);
            $charts2 = $chartsService->getChartPerAmount($rows);
            $charts3 = $chartsService->getChartPerSum($rows);
        } catch (Throwable $throwable) {
            Log::critical($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
        }

        return view('fundraising.show-analytics', compact('fundraising', 'rows', 'charts', 'charts2', 'charts3'));

    }

    /**
     * @param array $validated
     * @return array
     */
    protected function getDetailParams(array $validated): array
    {
        $cardMono = $cardPrivat = $paypal = null;
        if ($validated['card_mono'] ?? null) {
            $cardMono = $validated['card_mono'];
            unset($validated['card_mono']);
        }
        if ($validated['card_privat'] ?? null) {
            $cardPrivat = $validated['card_privat'];
            unset($validated['card_privat']);
        }
        if ($validated['paypal'] ?? null) {
            $paypal = $validated['paypal'];
            unset($validated['paypal']);
        }

        return [$paypal, $cardPrivat, $cardMono, $validated];
    }
}
