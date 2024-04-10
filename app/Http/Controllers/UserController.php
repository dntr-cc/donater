<?php

namespace App\Http\Controllers;

use App\Events\OpenGraphRegenerateEvent;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\ChartService;
use App\Services\FileService;
use App\Services\RowCollectionService;
use Cache;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public const string VOLUNTEERS = 'Волонтери';
    const string USERS = 'Донатери';

    public function show(User $user)
    {
        if ($user->isForget()) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        $dntr = (bool)Cache::pull(':fg:' . sha1(request()->userAgent() . implode(request()->ips())));
        if ($dntr) {
            Cache::set('referral_fg:' . sha1(request()->userAgent() . implode(request()->ips())), $user->getId(), 60 * 60);
        }
        return static::renderUserView($user, $dntr, $user->getFundraisings());
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function volunteers(): View
    {
        $users = User::query()->where('forget', '=', false)->without(['settings'])->withWhereHas('fundraisings')->get();
        $whoIs = self::VOLUNTEERS;

        return view('users', compact('users', 'whoIs'));
    }

    public function updateAvatar(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $avatar = app(FileService::class)->createAvatar($request, '/images/avatars/');
        $user->update(['avatar' => $avatar]);
        OpenGraphRegenerateEvent::dispatch($user->getId(), OpenGraphRegenerateEvent::TYPE_USER);
        foreach ($user->getFundraisings() as $fundraising) {
            OpenGraphRegenerateEvent::dispatch($fundraising->getId(), OpenGraphRegenerateEvent::TYPE_FUNDRAISING);
        }

        return new JsonResponse(['url' => route('user', compact('user')), 'csrf' => $this->getNewCSRFToken()]);
    }

    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user->update($request->validated());
        OpenGraphRegenerateEvent::dispatch($user->getId(), OpenGraphRegenerateEvent::TYPE_USER);
        foreach ($user->getFundraisings() as $fundraising) {
            OpenGraphRegenerateEvent::dispatch($fundraising->getId(), OpenGraphRegenerateEvent::TYPE_FUNDRAISING);
        }

        return new JsonResponse(['url' => route('user', compact('user')), 'csrf' => $this->getNewCSRFToken()]);
    }

    /**
     * @return int
     */
    protected function getUsersPerPage(): int
    {
        return config('app.per_page.users') ?? 12;
    }

    /**
     * @return int
     */
    protected function getVolunteersPerPage(): int
    {
        return config('app.per_page.volunteers') ?? 12;
    }

    /**
     * @param User $user
     * @param bool $dntr
     * @param Collection|null $fundraisings
     * @return \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View|Application
     */
    public static function renderUserView(
        User $user,
        bool $dntr,
        Collection $fundraisings = null
    ): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\View|Application|Factory {
        $rowCollectionService = app(RowCollectionService::class);
        $donaterRows = $rowCollectionService->getRowCollectionByDonates($user->getDonates());
        $chartsService = app(ChartService::class);
        $donaterCharts = $chartsService->getChartPerDay($donaterRows, 'linePerDay1');
        $donaterCharts2 = $chartsService->getChartPerAmount($donaterRows, 'piePerAmount1');
        $donaterCharts3 = $chartsService->getChartPerSum($donaterRows, 'piePerSum1');
        $rows = $charts = $charts2 = $charts3 = null;
        if (auth()?->user()?->can('update', $user) && $fundraisings) {
            $rows = $rowCollectionService->getRowCollection($fundraisings);
            $charts = $chartsService->getChartPerDay($rows, 'linePerDay2');
            $charts2 = $chartsService->getChartPerAmount($rows, 'piePerAmount2');
            $charts3 = $chartsService->getChartPerSum($rows, 'piePerSum2');
        }

        return view('user', [
            'dntr'           => $dntr,
            'user'           => $user,
            'rows'           => $rows,
            'charts'         => $charts,
            'charts2'        => $charts2,
            'charts3'        => $charts3,
            'donaterRows'    => $donaterRows,
            'donaterCharts'  => $donaterCharts,
            'donaterCharts2' => $donaterCharts2,
            'donaterCharts3' => $donaterCharts3,
        ]);
    }
}
