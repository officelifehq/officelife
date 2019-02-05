<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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

        Cache::put('currentCompany', $company, now()->addMinutes(60));

        if ($employee) {
            return $next($request);
        }

        abort(401);
    }
}
