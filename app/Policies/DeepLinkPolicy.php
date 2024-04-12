<?php

namespace App\Policies;

use App\Models\DeepLink;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeepLinkPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, DeepLink $deepLink): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, DeepLink $deepLink): bool
    {
    }

    public function delete(User $user, DeepLink $deepLink): bool
    {
    }

    public function restore(User $user, DeepLink $deepLink): bool
    {
    }

    public function forceDelete(User $user, DeepLink $deepLink): bool
    {
    }
}
