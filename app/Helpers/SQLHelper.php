<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class SQLHelper
{
    /**
     * Performs a concatenation in SQL.
     *
     * @param string $firstParam
     * @param string $secondParam
     * @return string
     */
    public static function concat(string $firstParam, string $secondParam): string
    {
        /** @var \Illuminate\Database\Connection */
        $connection = DB::connection();

        if ($connection->getDriverName() == 'sqlite') {
            $query = $firstParam.' || '.$secondParam;
        } else {
            $query = 'concat('.$firstParam.', " ", '.$secondParam.')';
        }

        return $query;
    }
}
