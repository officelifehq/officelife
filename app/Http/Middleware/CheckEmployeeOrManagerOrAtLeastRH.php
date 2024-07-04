<?php

namespace App\Http\Middleware;

use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CheckEmployeeOrManagerOrAtLeastRH
{
    /**
     * Check that only the employee who owns this information can access it,
     * or his manager
     * or someone with RH or admin role.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $employee = InstanceHelper::getLoggedEmployee();

        $requestedEmployee = $request->route()->parameters()['employee'];

        try {
            $requestedEmployee = Employee::findOrfail($requestedEmployee);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }

        if ($requestedEmployee->id == $employee->id) {
            return $next($request);
        }

        if ($employee->isManagerOf($requestedEmployee->id)) {
            return $next($request);
        }

        if ($employee->permission_level <= config('officelife.permission_level.hr')) {
            return $next($request);
        }

        return redirect('home');
        //abort(401);
    }
}
