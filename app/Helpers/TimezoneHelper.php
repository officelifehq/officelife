<?php

namespace App\Helpers;

use DateTimeZone;
use Carbon\Carbon;

class TimezoneHelper
{
    /**
     * Get the list of timezones.
     *
     * @return array
     */
    public static function getListOfTimezones(): array
    {
        $timezones = DateTimeZone::listIdentifiers();

        $timezoneCollection = collect();
        foreach ($timezones as $timezone) {
            $formatedTimezone = self::formatTimezone($timezone);
            $timezoneCollection->push([
                'value' => $formatedTimezone['name'],
                'label' => $formatedTimezone['friendly_name'],
                'sort_key' => $formatedTimezone['offset'],
            ]);
        }

        // we need to remove the keys we don't need as the view needs only 2 keys
        $timezoneCollection = $timezoneCollection->sortBy('sort_key');
        $timezoneCollection = $timezoneCollection->map(function ($item) {
            return array_only($item, ['value', 'label']);
        });

        // we need to reset the keys before converting it to an array
        // otherwise, Vue doesn't like it
        return $timezoneCollection->values()->toArray();
    }

    /**
     * Format a timezone to be displayed (english locale only).
     *
     * @param string $timezone
     * @return array
     */
    private static function formatTimezone(string $timezone): array
    {
        $offset = self::getOffset($timezone);

        if ($timezone == 'UTC') {
            $timezoneName = 'UTC';
        } else {
            $timezoneName = Carbon::now($timezone)->tzName;
        }

        $friendlyName = self::getTimezoneFriendlyName($timezoneName, $offset);

        return [
            'name' => $timezoneName,
            'friendly_name' => $friendlyName,
            'offset' => $offset,
        ];
    }

    private static function getTimezoneFriendlyName(string $timezoneName, string $offset): string
    {
        $dtimezone = new DateTimeZone($timezoneName);
        $location = $dtimezone->getLocation();

        if ($timezoneName == 'UTC') {
            $friendlyName = '(UTC) Universal Time Coordinated';
        } else {
            if (empty($location['comments'])) {
                $friendlyName = '(UTC '.$offset.') '.$timezoneName;
            } else {
                $friendlyName = '(UTC '.$offset.') '.$location['comments'].' ('.$timezoneName.')';
            }
        }

        return $friendlyName;
    }

    private static function getOffset(string $timezoneName): string
    {
        return Carbon::now($timezoneName)->format('P');
    }

    /**
     * Get the value/label combo of the timezone in an array based on its codename.
     *
     * @param string $timezoneName
     * @return array
     */
    public static function getTimezoneKeyValue(string $timezoneName): array
    {
        $timezones = DateTimeZone::listIdentifiers();

        foreach ($timezones as $timezone) {
            if ($timezoneName == Carbon::now($timezone)->tzName) {
                return [
                    'value' => $timezoneName,
                    'label' => self::getTimezoneFriendlyName($timezoneName, self::getOffset($timezoneName)),
                ];
            }
        }

        return [];
    }
}
