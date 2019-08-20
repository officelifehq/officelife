<?php

namespace App\Helpers;

use App\Models\Company\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class InstanceHelper
{
    /**
     * Return the company as set in the cache.
     *
     * @return Company|null
     */
    public static function getLoggedCompany()
    {
        if (Auth::check()) {
            return Cache::get('cachedCompanyObject_'.Auth::user()->id);
        }
        return;
    }

    /**
     * Return the employee as set in the cache.
     *
     * @return Employee|null
     */
    public static function getLoggedEmployee()
    {
        if (Auth::check()) {
            return Cache::get('cachedCompanyEmployee_' . Auth::user()->id);
        }

        return;
    }
}
