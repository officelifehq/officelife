<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Company\Employee;
use App\Models\Company\Company;

class CheckCompany
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
        $company = Company::findOrFail($request->route()->parameter('company'));

        $employee = Employee::where('user_id', auth()->user()->id)
            ->where('company_id', $company->id)
            ->first();

        if ($employee) {
            return $next($request);
        }

        abort(401);
    }
}
