<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdministratorRole
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
        if (config('homas.authorizations.administrator') >= auth()->user()->permission_level) {
            return $next($request);
        }

        abort(401);
    }
}
