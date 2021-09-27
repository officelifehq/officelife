<?php

namespace App\Http\ViewHelpers\Company\Project;

use App\Models\Company\Project;

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
}
