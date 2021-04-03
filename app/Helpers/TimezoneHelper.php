<?php

namespace App\Helpers;

use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TimezoneHelper
{
    /**
     * Get the list of timezones.
     *
     * @param int $minutes
     * @return Collection
     */
    public static function getListOfTimezones(): Collection
    {
        $timezones = DateTimeZone::listIdentifiers();

        $timezoneCollection = collect();
        $numberOfTimezones = 0;
        foreach ($timezones as $timezone) {
            $array = self::formatTimezone($timezone);
            $timezoneCollection->push([
                'value' => $numberOfTimezones,
                'label' => $array['name'],
                'sort_key' => $array['offset'],
            ]);

            $numberOfTimezones++;
        }

        $timezoneCollection = $timezoneCollection->sortBy('sort_key');
        $timezoneCollection = $timezoneCollection->map(function ($item) {
            return array_only($item, ['value', 'label']);
        });

        return $timezoneCollection;
    }

    /**
     * Format a timezone to be displayed (english only).
     *
     * @param string $timezone
     * @return array
     */
    private static function formatTimezone(string $timezone): array
    {
        $dtimezone = new DateTimeZone($timezone);
        $time = Carbon::now($timezone);

        $offset = $time->format('P');

        $loc = $dtimezone->getLocation();

        if ($timezone == 'UTC') {
            $friendlyName = '(UTC) Universal Time Coordinated';
        } else {
            $timezoneName = $time->tzName;

            if (empty($loc['comments'])) {
                $friendlyName = '(UTC '.$offset.') '.$timezoneName;
            } else {
                $friendlyName = '(UTC '.$offset.') '.$loc['comments'].' ('.$timezoneName.')';
            }
        }

        return [
            'name' => $friendlyName,
            'offset' => $offset,
        ];
    }

    /**
     * Get a friendly timezone name based on the index of the timezone.
     *
     * @param int $timezoneNumber
     * @return string
     */
    public static function getTimezoneNameById(int $timezoneNumber): string
    {
        $timezones = DateTimeZone::listIdentifiers();

        $timezoneCollection = collect();
        $numberOfTimezones = 0;
        foreach ($timezones as $timezone) {
            $array = self::formatTimezone($timezone);
            $timezoneCollection->push([
                'value' => $numberOfTimezones,
                'label' => $array['name'],
            ]);

            $numberOfTimezones++;
        }
    }
}
