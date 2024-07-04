<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class SQLHelper
{
    /**
     * Performs a concatenation in SQL.
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

    /**
     * Extract the year.
     */
    public static function year(string $dateColumnName): string
    {
        /** @var \Illuminate\Database\Connection */
        $connection = DB::connection();

        switch ($connection->getDriverName()) {
            case 'sqlite':
                $query = 'strftime("%Y", '.$dateColumnName.')';
                break;

            case 'pgsql':
                $query = 'date_part(\'year\', '.$dateColumnName.')';
                break;

            default:
                $query = 'YEAR('.$dateColumnName.')';
                break;
        }

        return $query;
    }

    /**
     * Extract the month.
     */
    public static function month(string $dateColumnName): string
    {
        /** @var \Illuminate\Database\Connection */
        $connection = DB::connection();

        switch ($connection->getDriverName()) {
            case 'sqlite':
                $query = 'strftime("%m", '.$dateColumnName.')';
                break;

            case 'pgsql':
                $query = 'date_part(\'month\', '.$dateColumnName.')';
                break;

            default:
                $query = 'MONTH('.$dateColumnName.')';
                break;
        }

        return $query;
    }
}
