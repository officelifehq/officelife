<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\InstanceHelper;

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
        $employee = InstanceHelper::getLoggedEmployee();

        if (config('villagers.authorizations.administrator') >= $employee->permission_level) {
            return $next($request);
        }

        abort(401);
    }
}
