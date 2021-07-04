<?php

namespace App\Http\ViewHelpers\Dashboard\HR;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\TimeHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use App\Models\Company\Timesheet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardHRTimesheetViewHelper
{
    /**
     * Get the information about timesheets that need approval for employees who
     * doesn't have a manager.
     *
     * @param Company $company
     * @return Collection|null
     */
    public static function timesheetApprovalsForEmployeesWithoutManagers(Company $company): ?Collection
    {
        // all the unapproved timesheets of employees without managers
        // except for the current week
        // this query is not super optimal. ideally, we would query timesheets +
        // employees without a manager in a single query, but I don’t know how
        // yet.
        $timesheets = Timesheet::where('company_id', $company->id)
            ->where('status', Timesheet::READY_TO_SUBMIT)
            ->with('employee')
            ->whereDate('started_at', '<', Carbon::now()->startOfWeek(Carbon::MONDAY))
            ->get();

        // get the list of employees with manager, that we flatten
        /** @phpstan-ignore-next-line */
        $listOfEmployeesWithManagers = DB::table('direct_reports')
            ->where('company_id', $company->id)
            ->select('employee_id')
            ->pluck('employee_id');

        $timesheetsWithUniqueEmployees = $timesheets->unique('employee_id');
        $timesheetsWithUniqueEmployees = $timesheetsWithUniqueEmployees->whereNotIn('employee_id', $listOfEmployeesWithManagers);

        $uniqueEmployeeCollection = collect([]);
        foreach ($timesheetsWithUniqueEmployees as $timesheet) {
            $employee = $timesheet->employee;
            $uniqueEmployeeCollection->push($employee);
        }

        $employeesCollection = collect([]);
        foreach ($uniqueEmployeeCollection as $employee) {
            $pendingTimesheets = $employee->timesheets()
                ->where('status', Timesheet::READY_TO_SUBMIT)
                ->orderBy('started_at', 'desc')
                ->get();

            $timesheetCollection = collect([]);
            foreach ($pendingTimesheets as $timesheet) {
                $totalWorkedInMinutes = DB::table('time_tracking_entries')
                ->where('timesheet_id', $timesheet->id)
                    ->sum('duration');

                $arrayOfTime = TimeHelper::convertToHoursAndMinutes($totalWorkedInMinutes);

                $timesheetCollection->push([
                    'id' => $timesheet->id,
                    'started_at' => DateHelper::formatDate($timesheet->started_at),
                    'ended_at' => DateHelper::formatDate($timesheet->ended_at),
                    'duration' => trans('dashboard.manager_timesheet_approval_duration', [
                        'hours' => $arrayOfTime['hours'],
                        'minutes' => $arrayOfTime['minutes'],
                    ]),
                    'url' => route('dashboard.hr.timesheet.show', [
                        'company' => $company,
                        'timesheet' => $timesheet,
                    ]),
                ]);
            }

            if ($pendingTimesheets->count() !== 0) {
                $employeesCollection->push([
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => ImageHelper::getAvatar($employee),
                    'position' => (! $employee->position) ? null : $employee->position->title,
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $employee,
                    ]),
                    'timesheets' => $timesheetCollection,
                ]);
            }
        }

        return $employeesCollection;
    }
}
