<?php

namespace App\Http\Middleware;

use App\Helpers\InstanceHelper;
use Closure;
use Illuminate\Http\Request;

class CheckAdministratorRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $employee = InstanceHelper::getLoggedEmployee();

        if (config('officelife.permission_level.administrator') >= $employee->permission_level) {
            return $next($request);
        }

        abort(401);
    }
}
