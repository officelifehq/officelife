<?php

namespace App\Helpers;

class FileHelper
{
    /**
     * Formats the file size to a human readable size.
     *
     * @param int $kb
     * @return string|null
     */
    public static function getSize(int $kb): ?string
    {
        $i = floor(log($kb, 1024));

        return round($kb / pow(1024, $i), [0, 0, 2, 2, 3][$i]).['kB', 'MB', 'GB', 'TB'][$i];
    }
}
