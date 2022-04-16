<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{
    /**
     * Display the login view.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function __invoke(Request $request): Response
    {
        /** @var Collection $providers */
        $providers = config('auth.login_providers');
        $providersName = [];
        foreach ($providers as $provider) {
            if ($name = config("services.$provider.name")) {
                $providersName[$provider] = $name;
            }
        }

        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
            'enableExternalLoginProviders' => config('auth.enable_external_login_providers'),
            'providers' => $providers,
            'providersName' => $providersName,
        ]);
    }
}
