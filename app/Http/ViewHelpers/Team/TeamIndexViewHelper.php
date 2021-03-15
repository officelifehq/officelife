<?php

namespace App\Http\ViewHelpers\Team;

use App\Helpers\AvatarHelper;
use App\Models\Company\Company;
use Illuminate\Support\Collection;

class TeamIndexViewHelper
{
    /**
     * Get all the teams in the company.
     *
     * @param Company $company
     * @return Collection
     */
    public static function index(Company $company): Collection
    {
        $teams = $company->teams()
            ->with('employees')
            ->orderBy('name', 'asc')
            ->get();

        $teamsCollection = collect([]);
        foreach ($teams as $team) {
            $employeeCollection = collect([]);
            foreach ($team->employees as $employee) {
                $employeeCollection->push([
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => AvatarHelper::getImage($employee, 20),
                    'position' => (! $employee->position) ? null : [
                        'id' => $employee->position->id,
                        'title' => $employee->position->title,
                    ],
                    'url' => route('employees.show', [
                        'company' => $employee->company,
                        'employee' => $employee,
                    ]),
                ]);
            }

            $teamsCollection->push([
                'id' => $team->id,
                'name' => $team->name,
                'employees' => $team->employees,
            ]);
        }

        return $teamsCollection;
    }
}
