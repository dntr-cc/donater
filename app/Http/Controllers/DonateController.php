<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonateRequest;
use App\Http\Resources\DonateResource;
use App\Models\Donate;

class DonateController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Donate::class);

        return DonateResource::collection(Donate::all());
    }

    public function store(DonateRequest $request)
    {
        $this->authorize('create', Donate::class);

        return new DonateResource(Donate::create($request->validated()));
    }

    public function show(Donate $donate)
    {
        $this->authorize('view', $donate);

        return new DonateResource($donate);
    }

    public function update(DonateRequest $request, Donate $donate)
    {
        $this->authorize('update', $donate);

        $donate->update($request->validated());

        return new DonateResource($donate);
    }

    public function destroy(Donate $donate)
    {
        $this->authorize('delete', $donate);

        $donate->delete();

        return response()->json();
    }
}
