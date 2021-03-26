<?php

namespace App\Http\ViewHelpers\Team;

use App\Helpers\ImageHelper;
use App\Models\Company\Team;
use App\Helpers\StringHelper;
use App\Models\Company\Company;
use Illuminate\Support\Collection;

class TeamShowViewHelper
{
    /**
     * Array containing all the basic information about the given team.
     *
     * @param Team $team
     *
     * @return array
     */
    public static function team(Team $team): array
    {
        return [
            'id' => $team->id,
            'name' => $team->name,
            'raw_description' => is_null($team->description) ? null : $team->description,
            'parsed_description' => is_null($team->description) ? null : StringHelper::parse($team->description),
            'team_leader' => is_null($team->leader) ? null : [
                'id' => $team->leader->id,
                'name' => $team->leader->name,
                'avatar' => ImageHelper::getAvatar($team->leader, 35),
                'position' => (! $team->leader->position) ? null : [
                    'title' => $team->leader->position->title,
                ],
            ],
        ];
    }

    /**
     * Collection containing all the employees in this team.
     *
     * @param Team $team
     *
     * @return Collection
     */
    public static function employees(Team $team): Collection
    {
        $employees = $team->employees()
            ->notLocked()
            ->with('position')
            ->with('company')
            ->orderBy('employee_team.created_at', 'desc')
            ->get();

        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 35),
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

        return $employeesCollection;
    }

    /**
     * Collection containing all the recent ships for this team.
     *
     * @param Team $team
     *
     * @return Collection
     */
    public static function recentShips(Team $team): Collection
    {
        $ships = $team->ships()->with('employees')
            ->take(3)
            ->orderBy('id', 'asc')
            ->get();

        $shipsCollection = collect([]);
        foreach ($ships as $ship) {
            $employees = $ship->employees;
            $employeeCollection = collect([]);
            foreach ($employees as $employee) {
                $employeeCollection->push([
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => ImageHelper::getAvatar($employee, 17),
                    'url' => route('employees.show', [
                        'company' => $team->company,
                        'employee' => $employee,
                    ]),
                ]);
            }

            $shipsCollection->push([
                'id' => $ship->id,
                'title' => $ship->title,
                'description' => $ship->description,
                'employees' => ($employeeCollection->count() > 0) ? $employeeCollection->all() : null,
                'url' => route('ships.show', [
                    'company' => $team->company,
                    'team' => $team,
                    'ship' => $ship->id,
                ]),
            ]);
        }

        return $shipsCollection;
    }

    /**
     * Search all potential leads for the team.
     *
     * @param Company $company
     * @param string $criteria
     * @return Collection
     */
    public static function searchPotentialLead(Company $company, string $criteria): Collection
    {
        $potentialEmployees = $company->employees()
            ->select('id', 'first_name', 'last_name')
            ->notLocked()
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('email', 'LIKE', '%'.$criteria.'%');
            })
            ->orderBy('last_name', 'asc')
            ->take(10)
            ->get();

        $employeesCollection = collect([]);
        foreach ($potentialEmployees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
            ]);
        }

        return $employeesCollection;
    }
}
