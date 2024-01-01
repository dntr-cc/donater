<?php

namespace App\Policies;

use App\Models\Fundraising;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FundraisingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(?User $user, Fundraising $fundraising): bool
    {
        return true;
    }

    public function create(?User $user): bool
    {
        return (bool)$user;
    }

    public function update(?User $user, Fundraising $fundraising): bool
    {
        return $user && $user->getId() === $fundraising->getUserId() || $user && $user->isSuperAdmin();
    }

    public function delete(?User $user, Fundraising $fundraising): bool
    {
        return $user && $user->getId() === $fundraising->getUserId() || $user && $user->isSuperAdmin();
    }
}
