<?php

namespace App\Helpers;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class InstanceHelper
{
    /**
     * Return the employee as set in the cache.
     *
     * @return Company
     */
    public static function getLoggedCompany()
    {
        if (Auth::check()) {
            return Cache::get('cachedCompanyObject_'.Auth::user()->id);
        }
    }

    /**
     * Return the employee as set in the cache.
     *
     * @return Employee
     */
    public static function getLoggedEmployee()
    {
        if (Auth::check()) {
            return Cache::get('cachedEmployeeObject_'.Auth::user()->id);
        }
    }
}
