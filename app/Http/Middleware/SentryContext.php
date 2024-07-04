<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Sentry\State\Scope;

class SentryContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     *
     * @codeCoverageIgnore
     */
    public function handle($request, Closure $next)
    {
        if (app()->bound('sentry') && config('app.sentry.enabled')) {
            \Sentry\configureScope(function (Scope $scope): void {
                // Add user context
                $user = Auth::user();
                if ($user !== null) {
                    $scope->setUser([
                        'id' => $user->id,
                    ]);
                }
                $scope->setTag('page.route.name', Route::currentRouteName() ?? '');
                $scope->setTag('page.route.action', Route::currentRouteAction() ?? '');
            });
        }

        return $next($request);
    }
}
