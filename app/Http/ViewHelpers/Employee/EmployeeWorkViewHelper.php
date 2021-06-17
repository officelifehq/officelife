<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use Illuminate\Support\Str;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Company;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ProjectTask;
use App\Models\Company\ProjectMessage;

class EmployeeWorkViewHelper
{
    /**
     * Get all worklogs with the morale for a given time period.
     * The page is structured as follow:
     * - list of 4 previous weeks
     * - for each week, the worklogs of the 5 working days + the morale of the
     * employee (if the logged employee has the right to view this information).
     *
     * @param Employee $employee
     * @param Employee $loggedEmployee
     * @param Carbon $startOfWeek
     * @param Carbon $selectedDay
     * @return array
     */
    public static function worklog(Employee $employee, Employee $loggedEmployee, Carbon $startOfWeek, Carbon $selectedDay): array
    {
        $worklogs = $employee->worklogs()->whereBetween('created_at', [$startOfWeek, $startOfWeek->copy()->endOfWeek()])->get();

        $daysCollection = collect();
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $worklog = $worklogs->first(function ($worklog) use ($day) {
                return $worklog->created_at->format('Y-m-d') == $day->format('Y-m-d');
            });

            $day = $startOfWeek->copy()->startOfWeek()->addDays($i);

            $daysCollection->push([
                'id' => Str::uuid()->toString(), // it doesn't matter here, this is just for Vue and having an unique key
                'day' => DateHelper::day($day),
                'day_number' => DateHelper::dayWithShortMonth($day),
                'date' => $day->format('Y-m-d'),
                'status' => DateHelper::determineDateStatus($day),
                'selected' => $selectedDay->format('Y-m-d') == $day->format('Y-m-d') ? 'selected' : '',
                'worklog_done_for_this_day' => $worklog ? 'green' : '',
            ]);
        }

        $worklog = $employee->worklogs()->whereDate('created_at', $selectedDay)->first();
        $morale = $employee->morales()->whereDate('created_at', $selectedDay)->first();

        $array = [
            'days' => $daysCollection,
            'current_week' => $startOfWeek->copy()->format('Y-m-d'),
            'id' => $worklog ? $worklog->id : null,
            'worklog_parsed_content' => $worklog ? StringHelper::parse($worklog->content) : null,
            'morale' => $morale && $loggedEmployee->id == $employee->id ? $morale->emoji : null,
        ];

        return $array;
    }

    /**
     * Get the current week date, and the three weeks prior to that.
     *
     * @param Employee $loggedEmployee
     * @return Collection
     */
    public static function weeks(Employee $loggedEmployee): Collection
    {
        $date = Carbon::now()->setTimezone($loggedEmployee->timezone);
        $currentWeek = $date->copy()->startofWeek();

        $weeksCollection = collect([]);
        $weeksCollection->push([
            'id' => 1,
            'label' => '3 weeks ago',
            'range' => [
                'start' => DateHelper::formatMonthAndDay($currentWeek->copy()->subWeeks(3)->startOfWeek()),
                'end' => DateHelper::formatMonthAndDay($currentWeek->copy()->subWeeks(3)->endOfWeek()),
            ],
            'start_of_week_date' => $currentWeek->copy()->subWeeks(3)->format('Y-m-d'),
        ]);
        $weeksCollection->push([
            'id' => 2,
            'label' => '2 weeks ago',
            'range' => [
                'start' => DateHelper::formatMonthAndDay($currentWeek->copy()->subWeeks(2)->startOfWeek()),
                'end' => DateHelper::formatMonthAndDay($currentWeek->copy()->subWeeks(2)->endOfWeek()),
            ],
            'start_of_week_date' => $currentWeek->copy()->subWeeks(2)->format('Y-m-d'),
        ]);
        $weeksCollection->push([
            'id' => 3,
            'label' => 'Last week',
            'range' => [
                'start' => DateHelper::formatMonthAndDay($currentWeek->copy()->subWeek()->startOfWeek()),
                'end' => DateHelper::formatMonthAndDay($currentWeek->copy()->subWeek()->endOfWeek()),
            ],
            'start_of_week_date' => $currentWeek->copy()->subWeek()->format('Y-m-d'),
        ]);
        $weeksCollection->push([
            'id' => 4,
            'label' => 'Current week',
            'range' => [
                'start' => DateHelper::formatMonthAndDay($currentWeek->copy()->startOfWeek()),
                'end' => DateHelper::formatMonthAndDay($currentWeek->copy()->endOfWeek()),
            ],
            'start_of_week_date' => $currentWeek->format('Y-m-d'),
        ]);

        return $weeksCollection;
    }

    /**
     * List all the projects of the employee.
     *
     * @param Employee $employee
     * @param Company $company
     * @return Collection|null
     */
    public static function projects(Employee $employee, Company $company): ?Collection
    {
        /** Going through a raw query coupled with eloquent to drastically reduce the number of hydrated models */
        $projects = Project::join('employee_project', 'employee_project.project_id', '=', 'projects.id')
                ->select('employee_project.role', 'employee_project.created_at', 'employee_project.project_id', 'projects.id as project_id', 'projects.name', 'projects.code', 'projects.status')
            ->addSelect([
                'messages_count' => ProjectMessage::select(DB::raw('count(id)'))
                ->whereColumn('author_id', 'employee_id')
                ->whereColumn('project_id', 'projects.id'),
            ])
            ->addSelect([
                'tasks_count' => ProjectTask::select(DB::raw('count(id)'))
                ->whereColumn('assignee_id', 'employee_id')
                ->whereColumn('project_id', 'projects.id'),
            ])
            ->where('employee_project.employee_id', $employee->id)
            ->orderBy('projects.id', 'desc')
            ->withCasts([
                'created_at' => 'datetime',
            ])
            ->get();

        $projectsCollection = collect([]);
        foreach ($projects as $project) {
            $projectsCollection->push([
                'id' => $project->project_id,
                'name' => $project->name,
                'code' => $project->code,
                'status' => $project->status,
                'role' => $project->role,
                'messages_count' => $project->messages_count,
                'tasks_count' => $project->tasks_count,
                'url' => route('projects.show', [
                    'company' => $company,
                    'project' => $project->project_id,
                ]),
            ]);
        }

        return $projectsCollection;
    }

    public static function groups(Employee $employee, Company $company): Collection
    {
        $groups = $employee->groups;

        $groupsCollection = collect([]);
        foreach ($groups as $group) {
            $members = $group->employees()
                ->inRandomOrder()
                ->take(3)
                ->get();

            $totalMembersCount = $group->employees()->count();
            $totalMembersCount = $totalMembersCount - $members->count();

            $membersCollection = collect([]);
            foreach ($members as $member) {
                $membersCollection->push([
                    'id' => $member->id,
                    'avatar' => ImageHelper::getAvatar($member, 25),
                    'url' => route('employees.show', [
                        'company' => $group->company_id,
                        'employee' => $member,
                    ]),
                ]);
            }

            $groupsCollection->push([
                'id' => $group->id,
                'name' => $group->name,
                'mission' => $group->mission ? StringHelper::parse($group->mission) : null,
                'preview_members' => $membersCollection,
                'remaining_members_count' => $totalMembersCount,
                'url' => route('groups.show', [
                    'company' => $company,
                    'group' => $group,
                ]),
            ]);
        }

        return $groupsCollection;
    }
}
