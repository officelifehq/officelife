<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

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
        $employee = Cache::get('cachedEmployeeObject');

        if ($employee->permission_level <= config('homas.authorizations.hr')) {
            return $next($request);
        }

        abort(401);
    }
}
