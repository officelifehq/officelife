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
    public static function convertToHoursAndMinutes(int $minutes = null): array
    {
        if (! $minutes || $minutes == 0) {
            return [
                'hours' => 0,
                'minutes' => 0,
            ];
        }

        $hours = floor($minutes / 60);
        $minutes = ($minutes % 60);

        // add leading zero
        $hours = str_pad((string) $hours, 2, '0', STR_PAD_LEFT);
        $minutes = str_pad((string) $minutes, 2, '0', STR_PAD_LEFT);

        return [
            'hours' => $hours,
            'minutes' => $minutes,
        ];
    }

    /**
     * Gets a sentence representing the time, like '2h12'.
     *
     * @param int $duration
     * @return string
     */
    public static function durationInHumanFormat(int $duration): string
    {
        $duration = self::convertToHoursAndMinutes($duration);

        $minutes = $duration['minutes'] == 0 ? '00' : $duration['minutes'];

        $time = trans('app.duration', [
            'hours' => $duration['hours'],
            'minutes' => $minutes,
        ]);

        $time = str_replace(' ', '', $time);

        return $time;
    }
}
