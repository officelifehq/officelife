<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

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
        $company = Cache::get('cachedCompanyObject');
        $employee = Cache::get('cachedEmployeeObject');

        if (config('homas.authorizations.administrator') >= $employee->permission_level) {
            return $next($request);
        }

        abort(401);
    }
}
