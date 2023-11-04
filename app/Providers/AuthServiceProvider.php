<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Donate;
use App\Models\UserLink;
use App\Models\Volunteer;
use App\Policies\DonatePolicy;
use App\Policies\UserLinkPolicy;
use App\Policies\VolunteerPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Donate::class    => DonatePolicy::class,
        Volunteer::class => VolunteerPolicy::class,
        UserLink::class  => UserLinkPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
