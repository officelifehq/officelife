<?php

namespace App\Providers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use App\Services\User\CreateAccount;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Services\User\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use App\Services\User\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
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
        Fortify::loginView(function () {
            return Inertia::render('Auth/Login', [
                'canResetPassword' => Route::has('password.request'),
                'status' => session('status'),
                'providers' => config('auth.login_providers'),
                'providersName' => ['saml2' => config('services.saml2.name')],
            ]);
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
