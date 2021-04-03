<?php

namespace App\Http\ViewHelpers\Team;

use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
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
            ->orderBy('name', 'asc')
            ->get();

        $teamsCollection = collect([]);
        foreach ($teams as $team) {
            $employeesCollection = collect([]);
            foreach ($team->employees()->with('picture')->get() as $employee) {
                $employeesCollection->push([
                    'id' => $employee->id,
                    'avatar' => ImageHelper::getAvatar($employee, 20),
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $employee,
                    ]),
                ]);
            }

            $teamsCollection->push([
                'id' => $team->id,
                'name' => $team->name,
                'parsed_description' => is_null($team->description) ? null : StringHelper::parse($team->description),
                'employees' => $employeesCollection,
                'url' => route('team.show', [
                    'company' => $company,
                    'team' => $team,
                ]),
            ]);
        }

        return $teamsCollection;
    }
}
