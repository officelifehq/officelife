<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
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
    public static function information(Employee $employee): array
    {
        $activeRate = $employee->consultantRates()->where('active', true)->first();
        $previousRate = $employee->consultantRates()->where('active', false)->orderBy('id', 'desc')->first();

        return [
            'id' => $employee->id,
            'name' => $employee->name,
            'avatar' => ImageHelper::getAvatar($employee, 100),
            'hired_at' => (! $employee->hired_at) ? null : [
                'full' => DateHelper::formatDate($employee->hired_at),
            ],
            'contract_renewed_at' => (! $employee->contract_renewed_at) ? null : DateHelper::formatDate($employee->contract_renewed_at),
            'contract_rate' => (! $activeRate) ? null : [
                'rate' => $activeRate->rate,
                'currency' => $employee->company->currency,
                'previous_rate' => $previousRate ? $previousRate->rate : null,
            ],
            'position' => (! $employee->position) ? null : [
                'id' => $employee->position->id,
                'title' => $employee->position->title,
            ],
            'pronoun' => (! $employee->pronoun) ? null : [
                'id' => $employee->pronoun->id,
                'label' => $employee->pronoun->label,
            ],
            'status' => (! $employee->status) ? null : [
                'id' => $employee->status->id,
                'name' => $employee->status->name,
                'external' => $employee->status->type == EmployeeStatus::EXTERNAL,
            ],
        ];
    }

    public static function yearsInCompany(Employee $employee, Company $company, int $selectedYear): ?Collection
    {
        if (is_null($employee->hired_at)) {
            return null;
        }

        $yearHired = $employee->hired_at->year;
        $numberOfYears = Carbon::now()->year - $yearHired;
        $yearsCollection = collect();
        for ($i = 0; $i <= $numberOfYears; $i++) {
            $yearsCollection->push([
                'year' => $yearHired,
                'selected' => $yearHired === $selectedYear,
                'url' => route('employee.show.whatsup.year', [
                    'company' => $company,
                    'employee' => $employee,
                    'year' => $yearHired,
                ]),
            ]);
            $yearHired = $yearHired + 1;
        }

        return $yearsCollection;
    }

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
            ->orderBy('id', 'desc')
            ->pluck('project_id')
            ->unique()
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

        if (Carbon::now()->isAfter($endDate)) {
            $numberOfDaysBetweenDates = $startDate->diffInDays($endDate);
        } else {
            $numberOfDaysBetweenDates = $startDate->diffInDays(Carbon::now());
        }

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

        if (Carbon::now()->isAfter($endDate)) {
            $numberOfDaysBetweenDates = $startDate->diffInDays($endDate);
        } else {
            $numberOfDaysBetweenDates = $startDate->diffInDays(Carbon::now());
        }

        return [
            'number_worklogs' => $worklogCount,
            'percent_completion' => round($worklogCount * 100 / $numberOfDaysBetweenDates),
        ];
    }
}
