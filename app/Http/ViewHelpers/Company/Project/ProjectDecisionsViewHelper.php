<?php

namespace App\Http\ViewHelpers\Company\Project;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;

class ProjectDecisionsViewHelper
{
    /**
     * Array containing the information about the decisions made in the project.
     *
     * @param Project $project
     * @param Employee $employee
     * @return Collection
     */
    public static function decisions(Project $project, Employee $employee): Collection
    {
        $company = $project->company;
        $decisions = $project->decisions()
            ->with('deciders')
            ->with('deciders.picture')
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
                    'avatar' => ImageHelper::getAvatar($decider, 22),
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $decider,
                    ]),
                ]);
            }

            $decisionsCollection->push([
                'id' => $decision->id,
                'title' => $decision->title,
                'decided_at' => DateHelper::formatDate($decision->decided_at, $employee->timezone),
                'deciders' => $decidersCollection,
            ]);
        }

        return $decisionsCollection;
    }

    /**
     * Search all employees matching a given criteria.
     *
     * @param Company $company
     * @param string|null $criteria
     * @return Collection
     */
    public static function searchDeciders(Company $company, ?string $criteria): Collection
    {
        return $company->employees()
            ->select('id', 'first_name', 'last_name', 'avatar_file_id')
            ->notLocked()
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('email', 'LIKE', '%'.$criteria.'%');
            })
            ->orderBy('last_name', 'asc')
            ->take(10)
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => ImageHelper::getAvatar($employee, 23),
                ];
            });
    }
}
