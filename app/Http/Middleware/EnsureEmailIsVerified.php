<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified as Middleware;

class EnsureEmailIsVerified extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param mixed|null $redirectToRoute
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (config('mail.verify')) {
            return parent::handle($request, $next, $redirectToRoute);
        }

        return $next($request);
    }
}
