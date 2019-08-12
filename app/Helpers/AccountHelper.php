<?php

if (! function_exists('tenant')) {
    /**
     * Gets the URL prefixed by the account number.
     *
     * @param string $route
     * @return string
     */
    function tenant($route)
    {
        $company = \Illuminate\Support\Facades\Cache::get('cachedCompanyObject');

        return config('app.url').'/'.$company->id.$route;
    }
}
