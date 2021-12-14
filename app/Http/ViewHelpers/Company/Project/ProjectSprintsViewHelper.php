<?php

namespace App\Http\ViewHelpers\Company\Project;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ProjectBoard;
use App\Models\Company\ProjectSprint;

class ProjectSprintsViewHelper
{
    /**
     * All the data about the sprint: issues and sprint info.
     *
     * @param Project $project
     * @param ProjectBoard $projectBoard
     * @param ProjectSprint $sprint
     * @param Employee $employee
     * @return array
     */
    public static function sprintData(Project $project, ProjectBoard $projectBoard, ProjectSprint $sprint, Employee $employee): array
    {
        $company = $project->company;
        $issues = $sprint->issues()
            ->with('type')
            ->with('assignees')
            ->orderBy('position')->get();

        $issueCollection = collect();
        foreach ($issues as $issue) {
            $assigneesCollection = collect();
            foreach ($issue->assignees as $assignee) {
                $assigneesCollection->push([
                    'id' => $assignee->id,
                    'name' => $assignee->name,
                    'avatar' => ImageHelper::getAvatar($assignee, 25),
                    'url' => [
                        'show' => route('employees.show', [
                            'company' => $assignee->company_id,
                            'employee' => $assignee,
                        ]),
                    ],
                ]);
            }

            $issueCollection->push([
                'id' => $issue->id,
                'key' => $issue->key,
                'title' => $issue->title,
                'slug' => $issue->slug,
                'is_separator' => $issue->is_separator,
                'created_at' => DateHelper::formatMonthAndDay($issue->created_at),
                'story_points' => $issue->story_points,
                'type' => $issue->type ? [
                    'name' => $issue->type->name,
                    'icon_hex_color' => $issue->type->icon_hex_color,
                ] : null,
                'url' => [
                    'show' => route('projects.issues.show', [
                        'company' => $company,
                        'key' => $issue->key,
                        'slug' => $issue->slug,
                    ]),
                    'reorder' => route('projects.issues.store.order', [
                        'company' => $company,
                        'project' => $project,
                        'board' => $projectBoard,
                        'sprint' => $sprint,
                        'issue' => $issue->id,
                    ]),
                    'destroy' => route('projects.issues.destroy', [
                        'company' => $company,
                        'project' => $project,
                        'board' => $projectBoard,
                        'sprint' => $sprint,
                        'issue' => $issue->id,
                    ]),
                ],
            ]);
        }

        // check if the sprint is collapsed in the board
        $isCollapsed = DB::table('project_sprint_employee_settings')
            ->where('project_sprint_id', $sprint->id)
            ->where('employee_id', $employee->id)
            ->first();

        $sprintData = [
            'id' => $sprint->id,
            'name' => $sprint->name,
            'active' => $sprint->active,
            'is_board_backlog' => $sprint->is_board_backlog,
            'collapsed' => $isCollapsed ? $isCollapsed->collapsed : false,
            'issues' => $issueCollection,
            'url' => [
                'store' =>  route('projects.issues.store', [
                    'company' => $company,
                    'project' => $project,
                    'board' => $projectBoard,
                    'sprint' => $sprint,
                ]),
                'toggle' => route('projects.sprints.toggle', [
                    'company' => $company,
                    'project' => $project,
                    'board' => $projectBoard,
                    'sprint' => $sprint,
                ]),
            ],
        ];

        return $sprintData;
    }
}
