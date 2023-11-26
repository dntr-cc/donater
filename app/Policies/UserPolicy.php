<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, User $targetUser): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(?User $user, User $targetUser): bool
    {
        return $user && $user->getId() === $targetUser->getId() || $user && $user->isSuperAdmin();
    }

    public function delete(?User $user, User $targetUser): bool
    {
        return $user && $user->getId() === $targetUser->getId() || $user && $user->isSuperAdmin();
    }
}
