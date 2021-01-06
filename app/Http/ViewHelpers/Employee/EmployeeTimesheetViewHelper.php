<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\SQLHelper;
use App\Helpers\DateHelper;
use App\Helpers\TimeHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EmployeeTimesheetViewHelper
{
    /**
     * Gets a collection of timesheets.
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

            $timesheetCollection->push([
                'id' => $timesheet->id,
                'started_at' => DateHelper::formatDate($timesheet->started_at),
                'ended_at' => DateHelper::formatDate($timesheet->ended_at),
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
     * @param Employee $employee
     * @param Company $company
     * @return Collection
     */
    public static function yearsWithTimesheets(Employee $employee, Company $company): Collection
    {
        $timesheets = Timesheet::where('employee_id', $employee->id)
            ->select(DB::raw(SQLHelper::year('started_at').' as year'))
            ->distinct()
            ->orderBy('year', 'asc')
            ->get();

        $yearsCollection = collect([]);
        foreach ($timesheets as $timesheet) {
            $yearsCollection->push([
                'number' => intval($timesheet['year']),
                'url' => route('employee.timesheets.year', [
                    'company' => $company,
                    'employee' => $employee,
                    'year' => $timesheet['year'],
                ]),
            ]);
        }

        return $yearsCollection;
    }

    /**
     * Get a collection representing all the months the employee has a timesheet,
     * for a given year.
     *
     * @param Employee $employee
     * @param Company $company
     * @param int $year
     * @return Collection
     */
    public static function monthsWithTimesheets(Employee $employee, Company $company, int $year): Collection
    {
        $timesheets = Timesheet::where('employee_id', $employee->id)
            ->whereYear('started_at', (string) $year)
            ->get();

        $monthsCollection = collect([]);
        for ($month = 1; $month < 13; $month++) {
            $timesheetsInMonth = collect([]);
            $date = Carbon::createFromDate($year, $month, 1);

            foreach ($timesheets as $timesheet) {
                if ($timesheet->started_at->month === $month) {
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
