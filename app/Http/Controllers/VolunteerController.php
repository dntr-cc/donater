<?php

namespace App\Http\Controllers;

use App\Http\Requests\VolunteerRequest;
use App\Http\Resources\VolunteerResource;
use App\Models\Volunteer;
use App\Services\GoogleServiceSheets;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VolunteerController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Volunteer::class);

        return VolunteerResource::collection(Volunteer::all());
    }

    public function store(VolunteerRequest $request)
    {
        $this->authorize('create', Volunteer::class);

        $attributes = $request->validated();
        if ($attributes['user_id'] !== $request->user()->getId() && !$request->user()->isSuperAdmin()) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $volunteer = Volunteer::create($attributes);

        return new JsonResponse(['url' => route('volunteer.show', compact('volunteer'))]);
    }

    public function create()
    {
        return view('volunteer.create');
    }

    public function storeAvatar(Request $request): JsonResponse
    {
        $this->authorize('create', Volunteer::class);

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
        $this->authorize('create', Volunteer::class);

        $status = Volunteer::query()->where('key', '=', $request->get('key', ''))->count() > 0 ? Response::HTTP_CONFLICT : Response::HTTP_OK;

        return new JsonResponse(['csrf' => $this->getNewCSRFToken()], $status);
    }

    public function spreadsheet(Request $request): JsonResponse
    {
        $this->authorize('create', Volunteer::class);
        app(GoogleServiceSheets::class)->getRowCollection($request->get('spreadsheet_id'));

        return new JsonResponse(['csrf' => $this->getNewCSRFToken()], Response::HTTP_OK);
    }

    public function edit(Volunteer $volunteer)
    {
        $this->authorize('update', $volunteer);

        return view('volunteer.edit', compact('volunteer'));
    }

    public function show(Volunteer $volunteer)
    {
        $this->authorize('view', $volunteer);
        $rows = app(GoogleServiceSheets::class)->getRowCollection($volunteer->getSpreadsheetId(), $volunteer->getId());

        return view('volunteer.show', compact('volunteer', 'rows'));
    }

    public function start(Volunteer $volunteer)
    {
        $this->authorize('update', $volunteer);

        $volunteer->update(['is_enabled' => true]);

        return $this->getRedirectResponse($volunteer);
    }

    public function update(VolunteerRequest $request, Volunteer $volunteer)
    {
        $this->authorize('update', $volunteer);

        $volunteer->update($request->validated());

        return new JsonResponse(['url' => route('volunteer.show', compact('volunteer'))]);
    }

    /**
     * @param Volunteer $volunteer
     * @return RedirectResponse
     */
    protected function getRedirectResponse(Volunteer $volunteer): RedirectResponse
    {
        return redirect(
            route('volunteer.show', compact('volunteer')),
            Response::HTTP_FOUND
        )->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    public function stop(Volunteer $volunteer)
    {
        $this->authorize('update', $volunteer);

        $volunteer->update(['is_enabled' => false]);

        return $this->getRedirectResponse($volunteer);
    }

    public function destroy(Volunteer $volunteer)
    {
        $this->authorize('delete', $volunteer);

        $volunteer->delete();

        return response()->json();
    }
}
