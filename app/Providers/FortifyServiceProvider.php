<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use App\Services\User\CreateAccount;
use Illuminate\Support\ServiceProvider;
use App\Services\User\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use App\Services\User\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Auth\LoginController;
use App\Services\User\UpdateUserProfileInformation;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Fortify::loginView(function ($request) {
            return app()->call(LoginController::class, ['request' => $request]);
        });

        Fortify::createUsersUsing(CreateAccount::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
