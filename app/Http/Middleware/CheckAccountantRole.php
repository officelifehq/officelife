<?php

namespace App\Http\Middleware;

use App\Helpers\InstanceHelper;
use Closure;
use Illuminate\Http\Request;

class CheckAccountantRole
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

        if ($employee->can_manage_expenses) {
            return $next($request);
        }

        abort(401);
    }
}
