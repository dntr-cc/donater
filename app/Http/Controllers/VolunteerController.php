<?php

namespace App\Http\Controllers;

use App\Http\Requests\VolunteerRequest;
use App\Http\Resources\VolunteerResource;
use App\Models\Volunteer;
use App\Services\GoogleServiceSheets;

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

        return new VolunteerResource(Volunteer::create($request->validated()));
    }

    public function show(Volunteer $volunteer)
    {
        $this->authorize('view', $volunteer);
        $rows = app(GoogleServiceSheets::class)->getRowCollection($volunteer);

        return view('volunteer', compact('volunteer', 'rows'));
    }

    public function update(VolunteerRequest $request, Volunteer $volunteer)
    {
        $this->authorize('update', $volunteer);

        $volunteer->update($request->validated());

        return new VolunteerResource($volunteer);
    }

    public function destroy(Volunteer $volunteer)
    {
        $this->authorize('delete', $volunteer);

        $volunteer->delete();

        return response()->json();
    }
}
