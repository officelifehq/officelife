<?php

namespace App\Providers;

use Inertia\Inertia;
use App\Helpers\InstanceHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->registerInertia();
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }

    public function registerInertia()
    {
        Inertia::version(function () {
            return md5_file(public_path('mix-manifest.json'));
        });

        Inertia::share([
            'auth' => function () {
                return [
                    'user' => Auth::user() ? [
                        'id' => Auth::user()->id,
                        'first_name' => Auth::user()->first_name,
                        'last_name' => Auth::user()->last_name,
                        'email' => Auth::user()->email,
                        'name' => Auth::user()->name,
                        'show_help' => Auth::user()->show_help,
                    ] : null,
                    'company' => Auth::user() && ! is_null(InstanceHelper::getLoggedCompany()) ? InstanceHelper::getLoggedCompany(): null,
                    'employee' => Auth::user() && ! is_null(InstanceHelper::getLoggedEmployee()) ? [
                        'id' => InstanceHelper::getLoggedEmployee()->id,
                        'first_name' => InstanceHelper::getLoggedEmployee()->first_name,
                        'last_name' => InstanceHelper::getLoggedEmployee()->last_name,
                        'name' => InstanceHelper::getLoggedEmployee()->name,
                        'permission_level' => InstanceHelper::getLoggedEmployee()->permission_level,
                        'user' => (! InstanceHelper::getLoggedEmployee()->user) ? null : [
                            'id' => InstanceHelper::getLoggedEmployee()->user_id,
                        ],
                    ]: null,
                ];
            },
            'help_links' => function () {
                return config('officelife.help_links');
            },
            'flash' => function () {
                return [
                    'success' => Session::get('success'),
                ];
            },
            'errors' => function () {
                return Session::get('errors')
                    ? Session::get('errors')->getBag('default')->getMessages()
                    : (object) [];
            },
        ]);
    }
}
