<?php

namespace App\Http\Controllers;

use App\Collections\RowCollection;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\ChartService;
use App\Services\FileService;
use App\Services\GoogleServiceSheets;
use Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public const string VOLUNTEERS = 'Волонтери';
    public const int PER_PAGE = 15;

    public function show(User $user)
    {
        $rows = $charts = $charts2 = $charts3 = null;
        $dntr = (bool)Cache::pull('fg:' . sha1(request()->userAgent() . implode(request()->ips())));
        if ($dntr) {
            Cache::set('referral_fg:' . sha1(request()->userAgent() . implode(request()->ips())), $user->getId(), 60 * 60);
        }
        if (auth()?->user()?->can('update', $user)) {
            $rows = new RowCollection();
            $service = app(GoogleServiceSheets::class);
            foreach ($user->getFundraisings() as $fundraising) {
                $rows->push(...$service->getRowCollection($fundraising->getSpreadsheetId(), $fundraising->getId())->all());
            }
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
        $users = User::paginate(self::PER_PAGE)->onEachSide(1);
        $whoIs = 'Користувачі';

        return view('users', compact('users', 'whoIs'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function volunteers(): View
    {
        $users = User::query()->withWhereHas('fundraisings')->paginate(self::PER_PAGE * 10)->onEachSide(1);
        $whoIs = self::VOLUNTEERS;

        return view('users', compact('users', 'whoIs'));
    }

    public function updateAvatar(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $avatar = app(FileService::class)->createAvatar($request, '/images/avatars/');
        $user->update(['avatar' => $avatar]);

        return new JsonResponse(['url' => route('user', compact('user')), 'csrf' => $this->getNewCSRFToken()]);
    }

    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user->update($request->validated());

        return new JsonResponse(['url' => route('user', compact('user')), 'csrf' => $this->getNewCSRFToken()]);
    }
}
