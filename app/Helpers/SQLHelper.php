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

        switch ($connection->getDriverName()) {
            case 'sqlite':
                $query = $firstParam.' || '.$secondParam;
                break;

            case 'pgsql':
                $query = $firstParam.' || '.$secondParam;
                break;

            default:
                $query = 'concat('.$firstParam.', " ", '.$secondParam.')';
                break;
        }

        return $query;
    }
}
