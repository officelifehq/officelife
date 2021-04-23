<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Models\Company\Company;
use App\Models\Company\Project;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Company\WorkFromHome;
use App\Models\Company\OneOnOneEntry;
use App\Models\Company\EmployeeStatus;
use App\Models\Company\TimeTrackingEntry;
use App\Models\Company\ProjectMemberActivity;

class EmployeeWhatsUpViewHelper
{
    public static function oneOnOnes(Employee $employee, Carbon $startDate, Carbon $endDate, Company $company): int
    {
        // number of one on ones, as direct report
        $oneOnOnesAsDirectReportCount = OneOnOneEntry::where('employee_id', $employee->id)
            ->whereBetween('happened_at', [$startDate, $endDate])
            ->count();

        return $oneOnOnesAsDirectReportCount;
    }

    public static function accomplishments(Employee $employee, Carbon $startDate, Carbon $endDate, Company $company): Collection
    {
        // accomplishments (aka recent ships)
        $recentShips = $employee->ships()
            ->whereBetween('ships.created_at', [$startDate, $endDate])
            ->get();

        $recentShipCollection = collect();
        foreach ($recentShips as $recentShip) {
            $recentShipCollection->push([
                'id' => $recentShip->id,
                'title' => $recentShip->title,
                'description' => $recentShip->description,
                'url' => route('ships.show', [
                    'company' => $company,
                    'team' => $recentShip->team_id,
                    'ship' => $recentShip->id,
                ]),
            ]);
        }

        return $recentShipCollection;
    }

    public static function projects(Employee $employee, Carbon $startDate, Carbon $endDate, Company $company): Collection
    {
        // projects worked on during the time frame
        $projectIds = ProjectMemberActivity::where('employee_id', $employee->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->pluck('project_id')
            ->unique('project_id')
            ->toArray();

        $projects = Project::whereIn('id', $projectIds)->get();

        $projectsCollection = collect();
        foreach ($projects as $project) {
            $projectsCollection->push([
                'id' => $project->id,
                'name' => $project->name,
                'code' => $project->code,
                'summary' => $project->summary,
                'status' => $project->status,
                'status_i18n' => trans('project.summary_status_'.$project->status),
                'url' => route('projects.show', [
                    'company' => $company,
                    'project' => $project,
                ]),
            ]);
        }

        return $projectsCollection;
    }

    public static function timesheets(Employee $employee, Carbon $startDate, Carbon $endDate): array
    {
        // get all timesheets information
        $timesheetEntries = Timesheet::query()
            ->where('employee_id', $employee->id)
            ->whereBetween('started_at', [$startDate, $endDate])
            ->addSelect([
                'minutes_worked_per_week' => TimeTrackingEntry::select(DB::raw('SUM(duration) as duration'))
                    ->whereColumn('timesheet_id', 'timesheets.id')
                    ->whereColumn('employee_id', $employee->id)
                    ->groupBy('timesheet_id'),
            ])
            ->get();

        $timesheetCollection = collect();
        foreach ($timesheetEntries as $entry) {
            $timesheetCollection->push([
                'id' => $entry->id,
                'started_at' => $entry->started_at->format('Y-m-d'),
                'ended_at' => $entry->ended_at->format('Y-m-d'),
                'minutes_worked' => $entry->minutes_worked_per_week,
            ]);
        }

        return [
            'average_hours_worked' => round($timesheetCollection->avg('minutes_worked') / 60),
            'data' => $timesheetCollection,
        ];
    }

    public static function external(Employee $employee, Carbon $startDate, Carbon $endDate)
    {
        if ($employee->status->type == EmployeeStatus::INTERNAL) {
            return;
        }

        $contractRenewInTimeframe = false;
        if ($employee->contract_renewed_at->between($startDate, $endDate)) {
            $contractRenewInTimeframe = true;
        }

        return [
            'contract_renews_in_timeframe' => $contractRenewInTimeframe,
            'hired_date' => DateHelper::formatDate($employee->contract_renewed_at),
        ];
    }

    public static function workFromHome(Employee $employee, Carbon $startDate, Carbon $endDate)
    {
        $workFromHomeCount = WorkFromHome::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->count();

        $numberOfDaysBetweenDates = $startDate->diffInDays($endDate);

        return [
            'number_times_work_from_home' => $workFromHomeCount,
            'percent_work_from_home' => round($workFromHomeCount * 100 / $numberOfDaysBetweenDates),
        ];
    }

    public static function worklogs(Employee $employee, Carbon $startDate, Carbon $endDate)
    {
        $worklogCount = Worklog::where('employee_id', $employee->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $numberOfDaysBetweenDates = $startDate->diffInDays($endDate);

        return [
            'number_worklogs' => $worklogCount,
            'percent_completion' => round($worklogCount * 100 / $numberOfDaysBetweenDates),
        ];
    }
}
