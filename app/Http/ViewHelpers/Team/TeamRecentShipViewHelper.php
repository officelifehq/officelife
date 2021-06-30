<?php

namespace App\Http\ViewHelpers\Team;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use App\Helpers\StringHelper;
use App\Models\Company\Company;
use Illuminate\Support\Collection;

class TeamRecentShipViewHelper
{
    /**
     * Collection containing all the recent ships for this team.
     *
     * @param Team $team
     *
     * @return Collection
     */
    public static function recentShips(Team $team): Collection
    {
        $ships = $team->ships()->orderBy('id', 'desc')->with('employees')->get();

        $shipsCollection = collect([]);
        foreach ($ships as $ship) {
            $employees = $ship->employees;
            $employeeCollection = collect([]);
            foreach ($employees as $employee) {
                $employeeCollection->push([
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => ImageHelper::getAvatar($employee, 21),
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
     * Collection containing the detail of a specific recent ship entry.
     *
     * @param Ship $ship
     *
     * @return array
     */
    public static function ship(Ship $ship): array
    {
        $employees = $ship->employees()->with('company')->with('position')->get();
        $team = $ship->team;

        $employeeCollection = collect([]);
        foreach ($employees as $employee) {
            $employeeCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 44),
                'position' => (! $employee->position) ? null : [
                    'title' => $employee->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $employee->company,
                    'employee' => $employee,
                ]),
            ]);
        }

        return [
            'id' => $ship->id,
            'title' => $ship->title,
            'description' => is_null($ship->description) ? null : StringHelper::parse($ship->description),
            'created_at' => DateHelper::formatFullDate($ship->created_at),
            'employees' => ($employeeCollection->count() > 0) ? $employeeCollection->all() : null,
            'url' => route('ships.show', [
                'company' => $team->company,
                'team' => $team,
                'ship' => $ship->id,
            ]),
        ];
    }

    /**
     * Search all potential team members for this ship.
     *
     * @param Company $company
     * @param string|null $criteria
     * @return Collection
     */
    public static function search(Company $company, ?string $criteria): Collection
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
