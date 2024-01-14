<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrizeRequest;
use App\Models\Prize;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrizeController extends Controller
{
    public function store(PrizeRequest $request)
    {
        $this->authorize('create', Prize::class);

        $attributes = $request->validated();

        if ((int)$attributes['user_id'] !== $request->user()->getId() && !$request->user()->isSuperAdmin()) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $prize = Prize::create($attributes);

        return new JsonResponse(['url' => route('my')]);
    }

    public function create()
    {
        $this->authorize('create', Prize::class);

        return view('prize.create');
    }

    public function show(Prize $prize)
    {
        return view('prize.show', compact('prize'));
    }

    public function storeAvatar(Request $request): JsonResponse
    {
        $this->authorize('create', Prize::class);

        $avatar = app(FileService::class)->createAvatar($request, '/images/prizes/');

        return new JsonResponse(['avatar' => url($avatar), 'csrf' => $this->getNewCSRFToken()]);
    }

    public function edit(Prize $prize)
    {
        $this->authorize('update', $prize);

        return view('prize.edit', compact('prize'));
    }

    public function update(PrizeRequest $request, Prize $prize)
    {
        $this->authorize('update', $prize);

        $prize->update($request->validated());

        return new JsonResponse(['url' => route('my')]);
    }

    public function approve(Prize $prize)
    {
        $this->authorize('update', $prize);

        $prize->setAvailableStatus(Prize::STATUS_GRANTED)->save();
        $prize->getVolunteer()->sendBotMessage(
            strtr('Запит на приз ":prize" було підтверджено', [':prize' => $prize->getName()])
        );

        return back();
    }

    public function decline(Prize $prize)
    {
        $this->authorize('update', $prize);

        $volunteer = $prize->getVolunteer();
        $prize->setAvailableStatus(Prize::STATUS_NEW)
            ->setFundraisingId()->save();
        $volunteer->sendBotMessage(
            strtr('Запит на приз ":prize" було скасовано', [':prize' => $prize->getName()])
        );

        return back();
    }

    public function destroy(Prize $prize)
    {
        $this->authorize('delete', $prize);

        $prize->delete();

        return response()->json();
    }

    /**
     * @param Prize $prize
     * @return JsonResponse
     */
    public function raffle(Prize $prize): JsonResponse
    {
        $this->authorize('update', $prize);

        $winners = $prize->getFundraising()
            ->rafflesPredictCollection()
            ->raffle($prize, true)->saveWinners($prize);

        return new JsonResponse(['html' => $winners->winnersToHtml(), 'csrf' => $this->getNewCSRFToken()]);
    }
}
