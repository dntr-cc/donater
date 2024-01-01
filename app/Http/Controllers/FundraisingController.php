<?php

namespace App\Http\Controllers;

use App\Http\Requests\FundraisingRequest;
use App\Models\Fundraising;
use App\Services\GoogleServiceSheets;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FundraisingController extends Controller
{
    public function store(FundraisingRequest $request)
    {
        $this->authorize('create', Fundraising::class);

        $attributes = $request->validated();

        if ((int)$attributes['user_id'] !== $request->user()->getId() && !$request->user()->isSuperAdmin()) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $fundraising = Fundraising::create($attributes);

        return new JsonResponse(['url' => route('fundraising.show', compact('fundraising'))]);
    }

    public function create()
    {
        return view('fundraising.create');
    }

    public function storeAvatar(Request $request): JsonResponse
    {
        $this->authorize('create', Fundraising::class);

        $uploadedFiles = $request->file('FILE');
        $fileName      = $uploadedFiles->getClientOriginalName();
        $username      = $request->user()->getUsername();
        $directory     = public_path('/images/banners/' . $username);
        $uploadedFiles->move($directory, $fileName);
        $avatar = '/images/banners/' . $username . '/' . $fileName;

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
        $rows = null;
        try {
            $rows = app(GoogleServiceSheets::class)->getRowCollection($fundraising->getSpreadsheetId(), $fundraising->getId());
        } catch (\Throwable $throwable) {
            Log::critical($throwable->getMessage(), ['trace' => $throwable->getTraceAsString()]);
        }

        return view('fundraising.show', compact('fundraising', 'rows'));
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

    public function raffle(Fundraising $fundraising)
    {
        $this->authorize('update', $fundraising);

        return view('fundraising.raffle', compact('fundraising'));
    }

    public function destroy(Fundraising $fundraising)
    {
        $this->authorize('delete', $fundraising);

        $fundraising->delete();

        return response()->json();
    }
}
