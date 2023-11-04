<?php

namespace App\Policies;

use App\Models\Donate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DonatePolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Donate $donate): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(?User $user, Donate $donate): bool
    {
        return $user && $user->getId() === $donate->getUserId() || $user && $user->isSuperAdmin();
    }

    public function delete(?User $user, Donate $donate): bool
    {
        return $user && $user->getId() === $donate->getUserId() || $user && $user->isSuperAdmin();
    }
}
