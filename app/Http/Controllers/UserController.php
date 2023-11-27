<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Nette\Schema\ValidationException;
use Storage;

class UserController extends Controller
{
    public function show(User $user)
    {
        return view('user', [
            'user' => $user,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $users = User::paginate(15)->fragment('users');

        return view('users', compact('users'));
    }

    public function updateAvatar(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $uploadedFiles = $request->file('FILE');
        $fileName      = $uploadedFiles->getClientOriginalName();
        $directory = public_path('/images/avatars/' . $user->getUsername());
        $uploadedFiles->move($directory, $fileName);
        $avatar = '/images/avatars/' . $user->getUsername() . '/' . $fileName;
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
