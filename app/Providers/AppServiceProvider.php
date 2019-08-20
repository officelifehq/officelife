<?php

namespace App\Providers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use App\Http\Resources\Company\Company\Company as CompanyResource;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class AppServiceProvider extends ServiceProvider
{
    protected $company;
    protected $employee;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->company = Cache::get('cachedCompanyObject');
        $this->employee = Cache::get('cachedEmployeeObject');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerInertia();
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
                        'permission_level' => Auth::user()->permission_level,
                    ] : null,
                    'company' => $this->company ? new CompanyResource($this->company) : null,
                    'employee' => $this->employee ? new EmployeeResource($this->employee) : null,
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
