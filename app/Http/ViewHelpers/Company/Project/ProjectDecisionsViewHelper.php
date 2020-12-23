<?php

namespace App\Http\ViewHelpers\Company\Project;

use App\Helpers\DateHelper;
use App\Models\Company\Project;
use Illuminate\Support\Collection;

class ProjectDecisionsViewHelper
{
    /**
     * Array containing the information about the decisions made in the project.
     *
     * @param Project $project
     * @return Collection
     */
    public static function decisions(Project $project): Collection
    {
        $company = $project->company;
        $decisions = $project->decisions()
            ->with('deciders')
            ->latest()
            ->get();

        $decisionsCollection = collect([]);
        foreach ($decisions as $decision) {
            $deciders = $decision->deciders;
            $decidersCollection = collect([]);
            foreach ($deciders as $decider) {
                $decidersCollection->push([
                    'id' => $decider->id,
                    'name' => $decider->name,
                    'avatar' => $decider->avatar,
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $decider,
                    ]),
                ]);
            }

            $decisionsCollection->push([
                'id' => $decision->id,
                'title' => $decision->title,
                'decided_at' => DateHelper::formatDate($decision->decided_at),
                'deciders' => $decidersCollection,
            ]);
        }

        return $decisionsCollection;
    }
}
