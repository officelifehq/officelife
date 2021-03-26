<?php

namespace App\Helpers;

class FileHelper
{
    /**
     * Formats the file size to a human readable size.
     *
     * @param int $bytes
     * @return string|null
     */
    public static function getSize(int $bytes): ?string
    {
        $i = floor(log($bytes, 1024));

        return round($bytes / pow(1024, $i), [0, 0, 2, 2, 3][$i]).['B', 'kB', 'MB', 'GB', 'TB'][$i];
    }
}
