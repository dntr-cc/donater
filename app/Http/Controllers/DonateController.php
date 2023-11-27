<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonateRequest;
use App\Http\Resources\DonateResource;
use App\Models\Donate;
use Symfony\Component\HttpFoundation\Response;

class DonateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', Donate::class);
        $donates = Donate::paginate(30)->fragment('donates');

        return view('donates', compact('donates'));
    }

    public function create()
    {
        return view('donate');
    }

    public function store(DonateRequest $request)
    {
        $this->authorize('create', Donate::class);

        Donate::create($request->validated());

        return redirect(route('my'), Response::HTTP_FOUND)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
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
