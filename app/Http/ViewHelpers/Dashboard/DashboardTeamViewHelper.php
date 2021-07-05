<?php

namespace App\Http\ViewHelpers\Dashboard;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Team;
use App\Helpers\BirthdayHelper;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use App\Helpers\WorkFromHomeHelper;

class DashboardTeamViewHelper
{
    /**
     * Array containing all the upcoming birthdays for employees in the given
     * team.
     *
     * @param Team $team
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
        $now = Carbon::now();

        foreach ($employees as $employee) {
            if (! $employee->birthdate) {
                continue;
            }

            if (BirthdayHelper::isBirthdayInXDays($now, $employee->birthdate, 30)) {
                $birthdaysCollection->push([
                    'id' => $employee->id,
                    'url' => route('employees.show', [
                        'company' => $employee->company,
                        'employee' => $employee,
                    ]),
                    'name' => $employee->name,
                    'avatar' => ImageHelper::getAvatar($employee, 35),
                    'birthdate' => DateHelper::formatMonthAndDay($employee->birthdate),
                    'sort_key' => Carbon::createFromDate($now->year, $employee->birthdate->month, $employee->birthdate->day)->format('Y-m-d'),
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
        $now = Carbon::now();

        foreach ($employees as $employee) {
            if (! WorkFromHomeHelper::hasWorkedFromHomeOnDate($employee, $now)) {
                continue;
            }

            $workFromHomeCollection->push([
                'id' => $employee->id,
                'url' => route('employees.show', [
                    'company' => $employee->company,
                    'employee' => $employee,
                ]),
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 35),
                'position' => $employee->position,
            ]);
        }

        return $workFromHomeCollection;
    }

    /**
     * Array containing all the teams.
     *
     * @param Collection $teams
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
     * @return Collection
     */
    public static function ships(Team $team): Collection
    {
        $ships = $team->ships()->orderBy('id', 'asc')->take(3)->get();

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
     * Creates an array containing all the information regarding the work logs
     * logged on the given day for a specific team.
     *
     * This array also contains an indicator telling how many team members have
     * filled the work logs for the day. The rules are as follow:
     * - less than 20% of team members have filled the work logs: red
     * - 20% -> 80%: yellow
     * - > 80%: green
     *
     * @param Team $team
     * @param Carbon $date
     * @param Employee $loggedEmployee
     * @return array
     */
    public static function worklogsForDate(Team $team, Carbon $date, Employee $loggedEmployee): array
    {
        $employees = $team->employees()->notLocked()->get();

        $numberOfEmployeesInTeam = $employees->count();
        $numberOfEmployeesWhoHaveLoggedWorklogs = count($team->worklogsForDate($date, $loggedEmployee));
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

    /**
     * Get all the work logs for the last 7 days.
     * By default, the view will display the following days:
     * Last Fri/M/T/W/T/F.
     *
     * @param Team $team
     * @param Carbon $startDate
     * @param Employee $loggedEmployee
     * @return Collection
     */
    public static function worklogsForTheLast7Days(Team $team, Carbon $startDate, Employee $loggedEmployee): Collection
    {
        $dates = collect([]);
        $lastFriday = $startDate->copy()->startOfWeek()->subDays(3);

        $dates->push(self::worklogsForDate($team, $lastFriday, $loggedEmployee));

        for ($i = 0; $i < 5; $i++) {
            $day = $startDate->copy()->startOfWeek()->addDays($i);
            $dates->push(self::worklogsForDate($team, $day, $loggedEmployee));
        }

        return $dates;
    }

    /**
     * Array containing all the upcoming new hires that will come on board in
     * the next week for this team.
     *
     * @param Team $team
     * @return Collection
     */
    public static function upcomingNewHires(Team $team): Collection
    {
        $now = Carbon::now();
        $currentDay = $now->format('Y-m-d');
        $nextSunday = $now->copy()->addWeek()->endOfWeek(Carbon::SUNDAY)->format('Y-m-d');

        $employees = $team->employees()
            ->notLocked()
            ->whereBetween('hired_at', [$currentDay, $nextSunday])
            ->get();

        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 35),
                'hired_at' => DateHelper::formatDayAndMonthInParenthesis($employee->hired_at),
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
     * Get all the upcoming hiring date anniversaries for employees in the team,
     * from now to 7 days frow now.
     *
     * @param Team $team
     * @return Collection
     */
    public static function upcomingHiredDateAnniversaries(Team $team): Collection
    {
        $employees = $team->employees()
            ->notLocked()
            ->whereNotNull('hired_at')
            ->whereYear('hired_at', '!=', Carbon::now()->year)
            ->get();

        $now = Carbon::now();
        $currentDay = $now->format('Y-m-d');
        $dayIn7DaysFromNow = $now->copy()->addDays(7)->format('Y-m-d');
        $next7Days = CarbonPeriod::create($currentDay, $dayIn7DaysFromNow);

        $employees = $employees->filter(function ($employee) use ($next7Days, $now) {
            return $next7Days->contains($employee->hired_at->setYear($now->year)->format('Y-m-d'));
        });

        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 35),
                'anniversary_date' => DateHelper::formatDayAndMonthInParenthesis($employee->hired_at->setYear($now->year)),
                'anniversary_age' => $now->year - $employee->hired_at->year,
                'url' => route('employees.show', [
                    'company' => $employee->company,
                    'employee' => $employee,
                ]),
            ]);
        }

        return $employeesCollection;
    }
}
