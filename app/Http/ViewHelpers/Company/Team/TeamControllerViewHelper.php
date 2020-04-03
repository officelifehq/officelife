<?php

namespace App\Http\ViewHelpers\Company\Dashboard;

use Carbon\Carbon;
use App\Models\Company\Team;
use Illuminate\Support\Collection;
use App\Helpers\WorkFromHomeHelper;

class DashboardTeamViewHelper
{
    /**
     * Array containing all the upcoming birthdays for employees in this team.
     * @param Team $team
     * @return Collection
     */
    public static function workFromHome(Team $team): Collection
    {
        $employees = $team->employees;

        $workFromHomeCollection = collect([]);
        foreach ($employees as $employee) {
            if (!WorkFromHomeHelper::hasWorkedFromHomeOnDate($employee, Carbon::now())) {
                continue;
            }

            $workFromHomeCollection->push([
                'id' => $employee->id,
                'url' => route('employees.show', [
                    'company' => $employee->company,
                    'employee' => $employee,
                ]),
                'name' => $employee->name,
                'avatar' => $employee->avatar,
                'position' => $employee->position,
            ]);
        }

        return $workFromHomeCollection;
    }
}
