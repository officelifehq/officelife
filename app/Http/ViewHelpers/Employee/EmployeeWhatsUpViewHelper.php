<?php

namespace App\Http\ViewHelpers\Employee;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\OneOnOneEntry;
use App\Models\Company\Project;
use App\Models\Company\ProjectMemberActivity;
use App\Models\Company\Timesheet;
use Carbon\Carbon;

class EmployeeWhatsUpViewHelper
{
    /**
     * Get the data required to display the What's up page about the given
     * employee.
     *
     * @param Employee $employee
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param Company $company
     * @return array
     */
    public static function index(Employee $employee, Carbon $startDate, Carbon $endDate, Company $company): array
    {
        // number of one on ones, as direct report
        $oneOnOnesAsDirectReportCount = OneOnOneEntry::where('employee_id', $employee->id)
            ->whereBetween('happened_at', [$startDate, $endDate])
            ->count();

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

        // get all timesheets information
        $timeTrackingEntries = Timesheet::query()
            ->where('employee_id', $employee->id)
            ->whereBetween('started_at', [$startDate, $endDate])
            ->addSelect([
                'hours_worked_per_week' => TimeTrackingEntry::select(DB::raw('SUM(duration) as duration'))
                    ->whereColumn('timesheet_id', 'timesheets.id')
                    ->groupBy('timesheet_id'),
            ])


        // all the teams the employee is part of
        $teamsId = $employee->teams->pluck('team.id')->toArray();

        return [
            'one_on_ones_as_direct_report_count' => $oneOnOnesAsDirectReportCount,
            'recent_ships' => $recentShipCollection,
            'projects' => $projectsCollection,
        ];
    }
}
