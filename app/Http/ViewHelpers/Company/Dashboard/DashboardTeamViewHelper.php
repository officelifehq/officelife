<?php

namespace App\Http\ViewHelpers\Company\Dashboard;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Models\Company\Team;
use App\Helpers\BirthdayHelper;

class DashboardTeamViewHelper
{
    /**
     * Array containing all the upcoming birthdays for employees in this team.
     * @param Team $team
     * @return array
     */
    public static function birthdays(Team $team): array
    {
        $employees = $team->employees;

        // remove employees without birthdates
        $employees = $employees->filter(function ($employee) {
            return !is_null($employee->birthdate);
        });

        // build the collection of data
        $birthdaysCollection = collect([]);
        foreach ($employees as $employee) {
            if (!$employee->birthdate) {
                continue;
            }

            if (BirthdayHelper::isBirthdaySoon(Carbon::now(), $employee->birthdate, 30)) {
                $birthdaysCollection->push([
                    'id' => $employee->id,
                    'url' => route('employees.show', [
                        'company' => $employee->company,
                        'employee' => $employee,
                    ]),
                    'name' => $employee->name,
                    'avatar' => $employee->avatar,
                    'birthdate' => DateHelper::formatMonthAndDay($employee->birthdate),
                    'sort_key' => Carbon::createFromDate(Carbon::now()->year, $employee->birthdate->month, $employee->birthdate->day)->format('Y-m-d'),
                ]);
            }
        }

        // sort the entries by soonest birthdates
        // we need to use values()->all() so it resets the keys properly.
        $birthdaysCollection = $birthdaysCollection->sortBy('sort_key')->values()->all();

        return $birthdaysCollection;
    }
}
