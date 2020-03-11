<?php

namespace App\Http\ViewHelpers\Company\Dashboard;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Models\Company\Team;
use App\Helpers\BirthdayHelper;
use Illuminate\Support\Collection;

class DashboardTeamViewHelper
{
    /**
     * Array containing all the upcoming birthdays for employees in this team.
     * @param Team $team
     * @return Collection
     */
    public static function birthdays(Team $team): Collection
    {
        $employees = $team->employees;

        // remove employees without birthdates
        $employees = $employees->filter(function ($employee) {
            return !is_null($employee->birthdate);
        });

        // build the collection of data
        $birthdaysCollection = collect();
        foreach ($employees as $employee) {
            if (!$employee->birthdate) {
                continue;
            }

            if (BirthdayHelper::isBirthdaySoon(Carbon::now(), $employee->birthdate, 30)) {
                $birthdaysCollection->push([
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => $employee->avatar,
                    'birthdate' => [
                        'full' => DateHelper::formatDate($employee->birthdate),
                        'year' => $employee->birthdate->year,
                        'month' => $employee->birthdate->month,
                        'day' => $employee->birthdate->day,
                        'age' => Carbon::now()->year - $employee->birthdate->year,
                    ],
                    'sort_key' => Carbon::createFromDate(Carbon::now()->year, $employee->birthdate->month, $employee->birthdate->day)->format('Y-m-d'),
                ]);
            }
        }

        // sort the entries by dates
        $birthdaysCollection = $birthdaysCollection->sortBy('sort_key');

        return $birthdaysCollection;
    }
}
