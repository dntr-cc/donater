<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLinkRequest;
use App\Http\Resources\UserLinkResource;
use App\Models\UserLink;

class UserLinkController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', UserLink::class);

        return UserLinkResource::collection(UserLink::all());
    }

    public function store(UserLinkRequest $request)
    {
        $this->authorize('create', UserLink::class);

        return new UserLinkResource(UserLink::create($request->validated()));
    }

    public function show(UserLink $userLink)
    {
        $this->authorize('view', $userLink);

        return new UserLinkResource($userLink);
    }

    public function update(UserLinkRequest $request, UserLink $userLink)
    {
        $this->authorize('update', $userLink);

        $userLink->update($request->validated());

        return new UserLinkResource($userLink);
    }

    public function destroy(UserLink $userLink)
    {
        $this->authorize('delete', $userLink);

        $userLink->delete();

        return response()->json();
    }
}
