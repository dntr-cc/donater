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

    public function destroy(Prize $prize)
    {
        $this->authorize('delete', $prize);

        $prize->delete();

        return response()->json();
    }
}
