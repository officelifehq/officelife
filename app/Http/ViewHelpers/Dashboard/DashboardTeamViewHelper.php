<?php

namespace App\Http\ViewHelpers\Dashboard;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Models\Company\Team;
use App\Helpers\BirthdayHelper;
use Illuminate\Support\Collection;
use App\Helpers\WorkFromHomeHelper;

class DashboardTeamViewHelper
{
    /**
     * Array containing all the upcoming birthdays for employees in the given
     * team.
     *
     * @param Team $team
     *
     * @return array
     */
    public static function birthdays(Team $team): array
    {
        $employees = $team->employees;

        // remove employees without birthdates
        $employees = $employees->filter(function ($employee) {
            return ! is_null($employee->birthdate);
        });

        // remove employees that are locked
        $employees = $employees->filter(function ($employee) {
            return ! $employee->locked;
        });

        // build the collection of data
        $birthdaysCollection = collect([]);
        foreach ($employees as $employee) {
            if (! $employee->birthdate) {
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

    /**
     * Array containing all employees who work from home in the given team.
     *
     * @param Team $team
     *
     * @return Collection
     */
    public static function workFromHome(Team $team): Collection
    {
        $employees = $team->employees;

        // remove employees that are locked
        $employees = $employees->filter(function ($employee) {
            return ! $employee->locked;
        });

        $workFromHomeCollection = collect([]);
        foreach ($employees as $employee) {
            if (! WorkFromHomeHelper::hasWorkedFromHomeOnDate($employee, Carbon::now())) {
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

    /**
     * Array containing all the teams.
     *
     * @param Collection $teams
     *
     * @return Collection
     */
    public static function teams(Collection $teams): Collection
    {
        $teamsCollection = collect([]);
        foreach ($teams as $team) {
            $teamsCollection->push([
                'id' => $team->id,
                'name' => $team->name,
                'url' => route('team.show', [
                    'company' => $team->company,
                    'team' => $team,
                ]),
            ]);
        }

        return $teamsCollection;
    }

    /**
     * Collection containing all the recent ships entry for the given team.
     *
     * @param Team $team
     *
     * @return Collection
     */
    public static function ships(Team $team): Collection
    {
        $ships = $team->ships()->get()->take(3);
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
     * Creates an array containing all the information regarding the worklogs
     * logged on the given day for a specific team.
     *
     * This array also contains an indicator telling how many team members have
     * filled the worklogs for the day. The rules are as follow:
     * - less than 20% of team members have filled the worklogs: red
     * - 20% -> 80%: yellow
     * - > 80%: green
     *
     * @param Team $team
     * @param Carbon $date
     *
     * @return array
     */
    public static function worklogs(Team $team, Carbon $date): array
    {
        // remove employees that are locked
        $employees = $team->employees;
        $employees = $employees->filter(function ($employee) {
            return ! $employee->locked;
        });

        $numberOfEmployeesInTeam = $employees->count();
        $numberOfEmployeesWhoHaveLoggedWorklogs = count($team->worklogsForDate($date));
        $percent = $numberOfEmployeesWhoHaveLoggedWorklogs * 100 / $numberOfEmployeesInTeam;

        $indicator = 'red';

        if ($percent >= 20 && $percent <= 80) {
            $indicator = 'yellow';
        }

        if ($percent > 80) {
            $indicator = 'green';
        }

        $data = [
            'day' => $date->isoFormat('dddd'),
            'date' => DateHelper::formatMonthAndDay($date),
            'friendlyDate' => $date->format('Y-m-d'),
            'status' => DateHelper::determineDateStatus($date),
            'completionRate' => $indicator,
            'numberOfEmployeesInTeam' => $numberOfEmployeesInTeam,
            'numberOfEmployeesWhoHaveLoggedWorklogs' => $numberOfEmployeesWhoHaveLoggedWorklogs,
        ];

        return $data;
    }
}
