<?php

namespace App\Policies;

use App\Models\Fundraising;
use App\Models\FundraisingShortCode;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FundraisingShortCodePolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, FundraisingShortCode $fundraisingShortCode): bool
    {
        return true;
    }

    public function create(?User $user, Fundraising $fundraising): bool
    {
        return $user && $user->getId() === $fundraising->getVolunteer()?->getId() || $user && $user->isSuperAdmin();
    }

    public function update(?User $user, FundraisingShortCode $fundraisingShortCode): bool
    {
        return $user && $user->getId() === $fundraisingShortCode->getFundraising()->getVolunteer()?->getId() || $user && $user->isSuperAdmin();
    }

    public function delete(?User $user, FundraisingShortCode $fundraisingShortCode): bool
    {
        return $user && $user->getId() === $fundraisingShortCode->getFundraising()->getVolunteer()?->getId() || $user && $user->isSuperAdmin();
    }
}
