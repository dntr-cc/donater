<?php

namespace App\Policies;

use App\Models\Subscribe;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscribePolicy
{
    use HandlesAuthorization;

    public function create(?User $user): bool
    {
        return (bool)$user;
    }

    public function update(?User $user, Subscribe $subscribe): bool
    {
        return $user && $user->getId() === $subscribe->getUserId() || $user && $user->isSuperAdmin();
    }

    public function delete(?User $user, Subscribe $subscribe): bool
    {
        return $user && $user->getId() === $subscribe->getUserId() || $user && $user->isSuperAdmin();
    }
}
