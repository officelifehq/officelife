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

        return [
            'hours' => $hours,
            'minutes' => $minutes,
        ];
    }
}
