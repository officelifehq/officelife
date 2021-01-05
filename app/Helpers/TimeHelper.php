<?php

namespace App\Helpers;

class TimeHelper
{
    /**
     * Convert a number of minutes to their equivalent in hours + minutes.
     *
     * @param int $minutes
     * @return array
     */
    public static function convertToHoursAndMinutes(int $minutes): array
    {
        $hours = floor($minutes / 60);
        $minutes = ($minutes % 60);

        // add leading zero
        $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);

        return [
            'hours' => $hours,
            'minutes' => $minutes,
        ];
    }
}
