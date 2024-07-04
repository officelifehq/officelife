<?php

namespace App\Http\Middleware;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CheckCompany
{
    /**
     * Check that the user can access this company.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $requestedCompanyId = $request->route()->parameter('company');

        try {
            $employee = Employee::where('user_id', Auth::user()->id)
                ->where('company_id', $requestedCompanyId)
                ->firstOrFail();

            if ($employee->locked) {
                abort(401);
            }

            $cachedCompanyObject = 'cachedCompanyObject_'.Auth::user()->id;
            $cachedEmployeeObject = 'cachedEmployeeObject_'.Auth::user()->id;

            Cache::put($cachedCompanyObject, $employee->company, now()->addMinutes(60));
            Cache::put($cachedEmployeeObject, $employee, now()->addMinutes(60));
        } catch (ModelNotFoundException $e) {
            abort(401);
        }

        return $next($request);
    }
}
