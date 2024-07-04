<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    /**
     * Display the login view.
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
