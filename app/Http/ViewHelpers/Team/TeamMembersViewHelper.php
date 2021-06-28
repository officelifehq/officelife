<?php

namespace App\Http\ViewHelpers\Team;

use App\Helpers\ImageHelper;
use App\Models\Company\Team;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;

class TeamMembersViewHelper
{
    /**
     * Search all potential members for the team.
     *
     * @param Company $company
     * @param Team $team
     * @param string $criteria
     * @return Collection
     */
    public static function searchPotentialTeamMembers(Company $company, Team $team, string $criteria): Collection
    {
        $potentialEmployees = $company->employees()
            ->select('id', 'first_name', 'last_name', 'email')
            ->notLocked()
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('email', 'LIKE', '%'.$criteria.'%');
            })
            ->orderBy('last_name', 'asc')
            ->take(10)
            ->get();

        $employeesInTeam = $team->employees()
            ->select('id', 'first_name', 'last_name')
            ->get();

        $potentialEmployees = $potentialEmployees->diff($employeesInTeam);

        $employeesCollection = collect([]);
        foreach ($potentialEmployees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
            ]);
        }

        return $employeesCollection;
    }

    /**
     * Array containing all the information about a specific employee.
     *
     * @param Employee $employee
     *
     * @return array
     */
    public static function employee(Employee $employee): array
    {
        return [
            'id' => $employee->id,
            'name' => $employee->name,
            'avatar' => ImageHelper::getAvatar($employee, 35),
            'position' => $employee->position,
        ];
    }
}
