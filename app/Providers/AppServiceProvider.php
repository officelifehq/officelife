<?php

namespace App\Providers;

use Inertia\Inertia;
use App\Helpers\InstanceHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use App\Http\Resources\Company\Company\Company as CompanyResource;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->registerInertia();
    }

    /**
     * Register any application services.
     *
     * @return void
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
                        'default_dashboard_view' => Auth::user()->default_dashboard_view,
                    ] : null,
                    'company' => Auth::user() && ! is_null(InstanceHelper::getLoggedCompany()) ? new CompanyResource(InstanceHelper::getLoggedCompany()) : null,
                    'employee' => Auth::user() && ! is_null(InstanceHelper::getLoggedEmployee()) ? new EmployeeResource(InstanceHelper::getLoggedEmployee()) : null,
                ];
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
