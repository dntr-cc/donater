<?php

namespace App\Policies;

use App\Models\Prize;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrizePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Prize $prize): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return (bool)$user;
    }

    public function update(?User $user, Prize $prize): bool
    {
        return $user && $user->getId() === $prize->getUserId() || $user && $user->isSuperAdmin();
    }

    public function delete(?User $user, Prize $prize): bool
    {
        return false;
    }

    public function restore(?User $user, Prize $prize): bool
    {
        return false;
    }

    public function forceDelete(?User $user, Prize $prize): bool
    {
        return false;
    }
}
