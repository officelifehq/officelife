<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\OneOnOneEntry;

class EmployeeWhatsUpViewHelper
{
    /**
     * Get the data required to display the What's up page about the given
     * employee.
     *
     * @param Employee $employee
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param Company $company
     * @return array
     */
    public static function index(Employee $employee, Carbon $startDate, Carbon $endDate, Company $company): array
    {
        // number of one on ones, as direct report
        $oneOnOnesAsDirectReportCount = OneOnOneEntry::where('employee_id', $employee->id)
            ->whereBetween('happened_at', [$startDate, $endDate])
            ->count();

        // all the teams the employee is part of
        $teamsId = $employee->teams->pluck('team.id')->toArray();

        // accomplishments (aka recent ships)
        $recentShips = $employee->ships()
            ->whereBetween('ships.created_at', [$startDate, $endDate])
            ->get();

        $recentShipCollection = collect();
        foreach ($recentShips as $recentShip) {
            $recentShipCollection->push([
                'id' => $recentShip->id,
                'title' => $recentShip->title,
                'description' => $recentShip->description,
                'url' => route('ships.show', [
                    'company' => $company,
                    'team' => $recentShip->team_id,
                    'ship' => $recentShip->id,
                ]),
            ]);
        }

        // projects worked on
        $projects = $employee->projects()
            ->whereBetween('ships.created_at', [$startDate, $endDate])
            ->get();

        return [
            'one_on_ones_as_direct_report_count' => $oneOnOnesAsDirectReportCount,
            'recent_ships' => $recentShipCollection,
        ];
    }
}
