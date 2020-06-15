<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;

class EmployeeWorkFromHomeViewHelper
{
    /**
     * Get a collection representing all the years the employee has been
     * working from home.
     *
     * @param Collection $workFromHomes
     *
     * @return Collection
     */
    public static function yearsWithEntries(Collection $workFromHomes): Collection
    {
        $yearsCollection = collect([]);
        foreach ($workFromHomes as $workFromHome) {
            $year = Carbon::createFromFormat('Y-m-d H:i:s', $workFromHome->date)->format('Y');
            $yearsCollection->push([
                'number' => intval($year),
            ]);
        }
        $yearsCollection = $yearsCollection->unique()->sortBy(function ($entry) {
            return $entry['number'];
        });

        return $yearsCollection;
    }

    /**
     * Get a collection representing all the months the employee has worked from
     * home, for a given year.
     *
     * @param Collection $entries
     * @param int $year
     *
     * @return Collection
     */
    public static function monthsWithEntries(Collection $entries, int $year): Collection
    {
        $entries = $entries->filter(function ($log) use ($year) {
            return $log->date->year === $year;
        });

        $monthsCollection = collect([]);

        for ($month = 1; $month < 13; $month++) {
            $logsInMonth = collect([]);
            $date = Carbon::createFromDate($year, $month, 1);

            foreach ($entries as $entry) {
                if ($entry->date->month === $month) {
                    $logsInMonth->push($entry);
                }
            }

            $monthsCollection->push([
                'month' => $month,
                'occurences' => $logsInMonth->count(),
                'translation' => DateHelper::translateMonth($date),
            ]);
        }

        return $monthsCollection;
    }
}
