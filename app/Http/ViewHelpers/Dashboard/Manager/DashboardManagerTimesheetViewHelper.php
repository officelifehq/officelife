<?php

namespace App\Http\ViewHelpers\Dashboard\Manager;

use App\Helpers\DateHelper;
use App\Helpers\TimeHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardManagerTimesheetViewHelper
{
    /**
     * Get the information about timesheets that need approval.
     *
     * @param Employee $manager
     * @param Collection $directReports
     * @return Collection|null
     */
    public static function timesheetApprovals(Employee $manager, Collection $directReports): ?Collection
    {
        $employeesCollection = collect([]);
        $company = $manager->company;

        foreach ($directReports as $directReport) {
            $employee = $directReport->directReport;

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
                    'url' => route('dashboard.manager.timesheet.show', [
                        'company' => $company,
                        'timesheet' => $timesheet,
                    ]),
                ]);
            }

            if ($pendingTimesheets->count() !== 0) {
                $employeesCollection->push([
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => ImageHelper::getAvatar($employee, 35),
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
