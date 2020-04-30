<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class SearchHelper
{
    /**
     * Build a query based on the array that contains column names.
     *
     * @param array $array
     * @param string $searchTerm
     *
     * @return string
     */
    public static function buildQuery(array $array, string $searchTerm): string
    {
        $first = true;
        $queryString = '';
        $searchTerms = explode(' ', $searchTerm);

        foreach ($searchTerms as $searchTerm) {
            $searchTerm = DB::connection()->getPdo()->quote('%'.$searchTerm.'%');

            foreach ($array as $column) {
                if ($first) {
                    $first = false;
                } else {
                    $queryString .= ' or ';
                }
                $queryString .= $column.' LIKE '.$searchTerm;
            }
        }

        return $queryString;
    }
}
