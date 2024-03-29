<?php

namespace App\Http\Controllers;

use App\Collections\RowCollection;
use App\Events\OpenGraphRegenerateEvent;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\ChartService;
use App\Services\FileService;
use App\Services\GoogleServiceSheets;
use App\Services\RowCollectionService;
use Cache;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class UserController extends Controller
{
    public const string VOLUNTEERS = 'Волонтери';
    const string USERS = 'Донатери';

    /**
     * @param User $user
     * @param Collection|null $fundraisings
     * @param bool $dntr
     * @return \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View|Application
     */
    protected static function getUserViewWithoutCache(
        User $user,
        ?Collection $fundraisings,
        bool $dntr
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

    public function show(User $user)
    {
        $dntr = (bool)Cache::pull(':fg:' . sha1(request()->userAgent() . implode(request()->ips())));
        if ($dntr) {
            Cache::set('referral_fg:' . sha1(request()->userAgent() . implode(request()->ips())), $user->getId(), 60 * 60);
        }
        return static::renderUserView($user, $dntr, $user->getFundraisings());
    }

    public function index(): View
    {
        $users = User::paginate($this->getUsersPerPage())->onEachSide(1);
        $whoIs = self::USERS;

        return view('users', compact('users', 'whoIs'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function volunteers(): View
    {
        $users = User::query()->withWhereHas('fundraisings')->paginate($this->getVolunteersPerPage())->onEachSide(1);
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
        return Cache::remember(
            strtr('view:userId:dntr:funds', [
                'userId' => sha1(serialize($user)),
                'dntr' => (int)$dntr,
                'funds' => sha1(serialize($fundraisings)),
            ]), 120, fn() => self::getUserViewWithoutCache($user, $fundraisings, $dntr));
    }
}
