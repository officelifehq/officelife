<?php

namespace App\Http\ViewHelpers\Company\Project;

use App\Models\Company\Company;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use App\Models\Company\ProjectBoard;

class ProjectBoardsViewHelper
{
    /**
     * All the boards in the project.
     *
     * @param Project $project
     * @return array
     */
    public static function index(Project $project): array
    {
        $company = $project->company;
        $boards = $project->boards()
            ->orderBy('name', 'asc')
            ->get();

        $boardsCollection = collect([]);
        foreach ($boards as $board) {
            $boardsCollection->push([
                'id' => $board->id,
                'name' => $board->name,
                'url' => route('projects.boards.show', [
                    'company' => $company,
                    'project' => $project,
                    'board' => $board,
                ]),
            ]);
        }

        return [
            'boards' => $boardsCollection,
            'url' => [
                'store' => route('projects.boards.store', [
                    'company' => $company,
                    'project' => $project,
                ]),
            ],
        ];
    }

    /**
     * Information needed for the Show board view.
     *
     * @param Project $project
     * @param ProjectBoard $projectBoard
     * @return array
     */
    public static function show(Project $project, ProjectBoard $projectBoard): array
    {
        $boardInformation = [
            'id' => $projectBoard->id,
            'name' => $projectBoard->name,
        ];

        return [
            'data' => $boardInformation,
            'url' => [],
        ];
    }

    /**
     * All the issue types in the company.
     *
     * @param Company $company
     * @return Collection
     */
    public static function issueTypes(Company $company): Collection
    {
        $types = $company->issueTypes()
            ->orderBy('name', 'asc')
            ->get();

        $typesCollection = collect([]);
        foreach ($types as $type) {
            $typesCollection->push([
                'id' => $type->id,
                'name' => $type->name,
                'icon_hex_color' => $type->icon_hex_color,
            ]);
        }
        return $typesCollection;
    }

    /**
     * Information needed for the Backlog view.
     *
     * @param Project $project
     * @param ProjectBoard $projectBoard
     * @param Employee $employee
     * @return array
     */
    public static function backlog(Project $project, ProjectBoard $projectBoard, Employee $employee): array
    {
        $sprintCollection = collect();

        $activeSprints = $projectBoard
            ->activeSprints()
            ->with('issues')
            ->with('issues.type')
            ->get();

        $backlog = $projectBoard->backlog()
            ->with('issues')
            ->with('issues.type')
            ->first();

        $activeSprints->push($backlog);

        foreach ($activeSprints as $sprint) {
            $data = ProjectSprintsViewHelper::sprintData($project, $projectBoard, $sprint, $employee);
            $sprintCollection->push($data);
        }

        $boardInformation = [
            'id' => $projectBoard->id,
            'name' => $projectBoard->name,
        ];

        return [
            'board' => $boardInformation,
            'sprints' => $sprintCollection,
        ];
    }
}
