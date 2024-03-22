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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public const string VOLUNTEERS = 'Волонтери';
    const string USERS = 'Донатери';

    public function show(User $user)
    {
        $rows = $charts = $charts2 = $charts3 = null;
        $dntr = (bool)Cache::pull('fg:' . sha1(request()->userAgent() . implode(request()->ips())));
        if ($dntr) {
            Cache::set('referral_fg:' . sha1(request()->userAgent() . implode(request()->ips())), $user->getId(), 60 * 60);
        }
        if (auth()?->user()?->can('update', $user)) {
            $rows = app(RowCollectionService::class)->getRowCollection($user->getFundraisings());
            $chartsService = app(ChartService::class);
            $charts = $chartsService->getChartPerDay($rows);
            $charts2 = $chartsService->getChartPerAmount($rows);
            $charts3 = $chartsService->getChartPerSum($rows);
        }

        return view('user', [
            'dntr' => $dntr,
            'user' => $user,
            'rows' => $rows,
            'charts' => $charts,
            'charts2' => $charts2,
            'charts3' => $charts3,
        ]);
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

        return new JsonResponse(['url' => route('user', compact('user')), 'csrf' => $this->getNewCSRFToken()]);
    }

    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user->update($request->validated());
        OpenGraphRegenerateEvent::dispatch($user->getId(), OpenGraphRegenerateEvent::TYPE_USER);

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
}
