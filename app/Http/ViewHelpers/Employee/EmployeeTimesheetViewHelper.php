<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\TimeHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Illuminate\Support\Collection;

class EmployeeTimesheetViewHelper
{
    /**
     * Gets a collection of timesheets.
     * Note that the timesheets object that we receive as parameter is not an
     * eloquent object, but the results of a raw query. Therefore we need to
     * parse the date as Carbon objects first before exploiting the dates.
     *
     * @param Collection $timesheets
     * @param Employee $employee
     * @param Company $company
     * @return array
     */
    public static function timesheets(Collection $timesheets, Employee $employee, Company $company): array
    {
        $timesheetCollection = collect([]);
        foreach ($timesheets as $timesheet) {
            $arrayOfTime = TimeHelper::convertToHoursAndMinutes($timesheet->duration);

            $startedAt = Carbon::createFromFormat('Y-m-d H:i:s', $timesheet->started_at);
            $endedAt = Carbon::createFromFormat('Y-m-d H:i:s', $timesheet->ended_at);

            $timesheetCollection->push([
                'id' => $timesheet->id,
                'started_at' => DateHelper::formatDate($startedAt),
                'ended_at' => DateHelper::formatDate($endedAt),
                'duration' => trans('dashboard.manager_timesheet_approval_duration', [
                    'hours' => $arrayOfTime['hours'],
                    'minutes' => $arrayOfTime['minutes'],
                ]),
                'status' => $timesheet->status,
                'url' => route('employee.timesheets.show', [
                    'company' => $company,
                    'employee' => $employee,
                    'timesheet' => $timesheet->id,
                ]),
            ]);
        }

        $numberOfApprovedTimesheets = $timesheetCollection->filter(function ($timesheet) {
            return $timesheet['status'] == Timesheet::APPROVED;
        });

        $numberOfRejectedTimesheets = $timesheetCollection->filter(function ($timesheet) {
            return $timesheet['status'] == Timesheet::REJECTED;
        });

        return [
            'entries' => $timesheetCollection,
            'statistics' => [
                'approved' => $numberOfApprovedTimesheets->count(),
                'rejected' => $numberOfRejectedTimesheets->count(),
            ],
        ];
    }

    /**
     * Get a collection representing all the years the employee has been
     * submitting a timesheet.
     *
     * @param Collection $timesheets
     * @param Employee $employee
     * @param Company $company
     * @return Collection
     */
    public static function yearsWithTimesheets(Collection $timesheets, Employee $employee, Company $company): Collection
    {
        $yearsCollection = collect([]);
        foreach ($timesheets as $timesheet) {
            $year = Carbon::createFromFormat('Y-m-d H:i:s', $timesheet->started_at)->format('Y');
            $yearsCollection->push([
                'number' => intval($year),
                'url' => route('employee.timesheets.year', [
                    'company' => $company,
                    'employee' => $employee,
                    'year' => $year,
                ]),
            ]);
        }

        $yearsCollection = $yearsCollection->unique()->sortBy(function ($entry) {
            return $entry['number'];
        });

        return $yearsCollection;
    }

    /**
     * Get a collection representing all the months the employee has a timesheet,\
     * for a given year.
     *
     * @param Collection $timesheets
     * @param Employee $employee
     * @param Company $company
     * @param int $year
     * @return Collection
     */
    public static function monthsWithTimesheets(Collection $timesheets, Employee $employee, Company $company, int $year): Collection
    {
        $timesheets = $timesheets->filter(function ($timesheet) use ($year) {
            $startedAt = Carbon::createFromFormat('Y-m-d H:i:s', $timesheet->started_at);

            return $startedAt->year === $year;
        });

        $monthsCollection = collect([]);

        for ($month = 1; $month < 13; $month++) {
            $timesheetsInMonth = collect([]);
            $date = Carbon::createFromDate($year, $month, 1);

            foreach ($timesheets as $timesheet) {
                $startedAt = Carbon::createFromFormat('Y-m-d H:i:s', $timesheet->started_at);
                if ($startedAt->month === $month) {
                    $timesheetsInMonth->push($timesheet);
                }
            }

            $monthsCollection->push([
                'month' => $month,
                'url' => route('employee.timesheets.month', [
                    'company' => $company,
                    'employee' => $employee,
                    'year' => $year,
                    'month' => $month,
                ]),
                'occurences' => $timesheetsInMonth->count(),
                'translation' => DateHelper::translateMonth($date),
            ]);
        }

        return $monthsCollection;
    }
}
