<?php

namespace App\Http\ViewHelpers\Team;

use App\Models\Company\Team;
use App\Helpers\StringHelper;
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
                'avatar' => $team->leader->avatar,
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
        $employees = $team->employees()->with('position')->with('company')->orderBy('employee_team.created_at', 'desc')->get();
        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => $employee->avatar,
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
        $ships = $team->ships()->with('employees')->get()->take(3);
        $shipsCollection = collect([]);
        foreach ($ships as $ship) {
            $employees = $ship->employees;
            $employeeCollection = collect([]);
            foreach ($employees as $employee) {
                $employeeCollection->push([
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => $employee->avatar,
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
                'employees' => ($employeeCollection->count() > 0) ? $employeeCollection->all(): null,
                'url' => route('ships.show', [
                    'company' => $team->company,
                    'team' => $team,
                    'ship' => $ship->id,
                ]),
            ]);
        }

        return $shipsCollection;
    }
}
