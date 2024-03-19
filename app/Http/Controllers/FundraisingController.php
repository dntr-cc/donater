<?php

namespace App\Http\Controllers;

use App\Http\Requests\FundraisingLinkCreateRequest;
use App\Http\Requests\FundraisingRequest;
use App\Models\Fundraising;
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

        $fundraising = Fundraising::create($attributes);
        $volunteer = $fundraising->getVolunteer();
        if (1 === $volunteer->getFundraisings()->count()) {
            $volunteer->sendBotMessage(
                'Вітаю! Ви створили свій перший збір. Долучайтеся до чату волонтерів нашого сайту ' .
                config('app.volunteer_chat_link') . ' Там волонтери шерять свій досвід, можуть задати питання, ' .
                ' попросити оперативно виправити знайдену багу тощо'
            );
        }

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

        return new JsonResponse(['avatar' => url($avatar), 'csrf' => $this->getNewCSRFToken()]);
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
        app(GoogleServiceSheets::class)->getRowCollection($request->get('spreadsheet_id'));

        return new JsonResponse(['csrf' => $this->getNewCSRFToken()], Response::HTTP_OK);
    }

    public function edit(Fundraising $fundraising)
    {
        $this->authorize('update', $fundraising);

        return view('fundraising.edit', compact('fundraising'));
    }

    public function show(Fundraising $fundraising)
    {
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

        return view('fundraising.show', compact('fundraising', 'rows', 'charts', 'charts2', 'charts3'));
    }

    public function start(Fundraising $fundraising)
    {
        $this->authorize('update', $fundraising);

        $fundraising->update(['is_enabled' => true]);

        return $this->getRedirectResponse($fundraising);
    }

    public function update(FundraisingRequest $request, Fundraising $fundraising)
    {
        $this->authorize('update', $fundraising);

        $fundraising->update($request->validated());

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
        $this->authorize('update', $fundraising);

        $fundraising->update(['is_enabled' => false]);

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
        $this->authorize('update', $fundraising);

        $prize->setFundraisingId()->setAvailableStatus(Prize::STATUS_NEW)->save();

        return new JsonResponse(['url' => route('fundraising.show', compact('fundraising'))]);
    }

    public function createShortLink(FundraisingLinkCreateRequest $request, Fundraising $fundraising): JsonResponse
    {
        $this->authorize('create', [FundraisingShortCode::class, $fundraising]);

        $data = $request->validated();
        app(FundraisingShortCodeService::class)
            ->createFundraisingShortCode($fundraising->getId(), $data['code'] ?? '');

        return new JsonResponse(['csrf' => $this->getNewCSRFToken()]);
    }
}
