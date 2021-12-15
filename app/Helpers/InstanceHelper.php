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
        if (Auth::check()) {
            return Cache::get('cachedEmployeeObject_'.Auth::user()->id);
        }
    }

    /**
     * Attempt to Retrieve Current Git Commit Hash in PHP.
     *
     * @return mixed
     */
    public static function getCurrentGitCommitHash()
    {
        $path = base_path('.git/');

        if (! file_exists($path)) {
            return null;
        }

        $head = trim(substr(file_get_contents($path . 'HEAD'), 4));
        $hash = trim(file_get_contents(sprintf($path . $head)));
        return $hash;
    }
}
