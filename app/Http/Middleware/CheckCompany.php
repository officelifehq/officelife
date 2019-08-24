<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Company\Company;
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
        $cachedCompanyObject = 'cachedCompanyObject_'.Auth::user()->id;
        $cachedEmployeeObject = 'cachedEmployeeObject_'.Auth::user()->id;

        $company = Cache::get($cachedCompanyObject);
        $employee = Cache::get($cachedEmployeeObject);

        // if there is no company in caching
        if (is_null($company)) {
            $company = Company::findOrFail($request->route()->parameter('company'));
            Cache::put($cachedCompanyObject, $company, now()->addMinutes(60));
        }

        // if the current company (in cache) doesn't match the company in the
        // route, refresh the cached object
        if ($request->route()->parameter('company') != $company->id) {
            $company = Company::findOrFail($request->route()->parameter('company'));
            Cache::put($cachedCompanyObject, $company, now()->addMinutes(60));
        }

        $employee = Auth::user()->getEmployeeObjectForCompany($company);
        Cache::put($cachedEmployeeObject, $employee, now()->addMinutes(60));

        if ($employee) {
            return $next($request);
        }

        abort(401);
    }
}
