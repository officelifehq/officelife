<?php

namespace App\Helpers;

use App\Models\Company\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class InstanceHelper
{
    /**
     * Return the company as set in the cache.
     *
     * @return mixed
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
     * @return mixed
     */
    public static function getLoggedEmployee()
    {
        $employee = Employee::where('user_id', Auth::user()->id)
            ->firstOrFail();

        return $employee;
    }
}
