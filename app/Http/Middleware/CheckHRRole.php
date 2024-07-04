<?php

namespace App\Http\Middleware;

use App\Helpers\InstanceHelper;
use Closure;
use Illuminate\Http\Request;

class CheckHRRole
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

        if ($employee->permission_level <= config('officelife.permission_level.hr')) {
            return $next($request);
        }

        abort(401);
    }
}
