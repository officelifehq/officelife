<?php

namespace App\Http\Middleware;

use Closure;

class CheckHRRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->permission_level <= config('homas.authorizations.hr')) {
            return $next($request);
        }

        abort(401);
    }
}
