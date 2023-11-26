<?php

namespace App\Policies;

use App\Models\Volunteer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VolunteerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(?User $user, Volunteer $volunteer): bool
    {
        return true;
    }

    public function create(?User $user): bool
    {
        return (bool)$user;
    }

    public function update(?User $user, Volunteer $volunteer): bool
    {
        return $user && $user->getId() === $volunteer->getUserId() || $user && $user->isSuperAdmin();
    }

    public function delete(?User $user, Volunteer $volunteer): bool
    {
        return $user && $user->getId() === $volunteer->getUserId() || $user && $user->isSuperAdmin();
    }
}
