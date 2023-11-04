<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserLink;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserLinkPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, UserLink $userLink): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(?User $user, UserLink $userLink): bool
    {
        return $user && $user->getId() === $userLink->getUserId() || $user && $user->isSuperAdmin();
    }

    public function delete(?User $user, UserLink $userLink): bool
    {
        return $user && $user->getId() === $userLink->getUserId() || $user && $user->isSuperAdmin();
    }
}
