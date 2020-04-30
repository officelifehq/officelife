<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;

class CheckHRRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     *
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
