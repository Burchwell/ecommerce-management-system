<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

/**
 * Class AuthServiceProvider
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        Passport::personalAccessClientId(config('passport.personal_access_client_id'));
        Passport::tokensExpireIn(now()->addDays(config('passport.access_token_expiry_days')));
        Passport::refreshTokensExpireIn(now()->addDays(config('passport.personal_refresh_token_expiry_days')));
        Passport::personalAccessTokensExpireIn(now()->addDays(config('passport.personal_access_token_expiry_days')));
    }
}
