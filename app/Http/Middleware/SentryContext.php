<?php

namespace App\Http\Middleware;

use Closure;
use Sentry\State\Scope;
use Illuminate\Support\Facades\Auth;

class SentryContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (app()->bound('sentry') && config('sentry.enable')) {
            // Add user context
            if (auth()->check()) {
                \Sentry\configureScope(function (Scope $scope): void {
                    $user = Auth::user();
                    $scope->setUser([
                        'id' => $user->id,
                        'email' => $user->email,
                        'username' => $user->name,
                    ]);
                    //$scope->setExtra('isSubscribed', $user->account->isSubscribed());
                });
            } else {
                \Sentry\configureScope(function (Scope $scope): void {
                    $scope->setUser([
                        'id' => null,
                        //'ip_address' => RequestHelper::ip(),
                    ]);
                });
            }
        }

        return $next($request);
    }
}
