<?php

if (!function_exists('tenant')) {
    /**
     * Gets the URL prefixed by the account number.
     *
     * @param string $route
     * @return string
     */
    function tenant($route)
    {
        return config('app.url').'/'.auth()->user()->account_id.$route;
    }
}
