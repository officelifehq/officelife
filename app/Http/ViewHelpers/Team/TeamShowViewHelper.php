<?php

namespace App\Http\ViewHelpers\Team;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Team;
use App\Helpers\StringHelper;
use App\Helpers\BirthdayHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use App\Models\Company\MoraleTeamHistory;

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
     * @param string|null $criteria
     * @return Collection
     */
    public static function searchPotentialLead(Company $company, ?string $criteria): Collection
    {
        return $company->employees()
            ->select('id', 'first_name', 'last_name', 'email')
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
                ];
            });
    }

    /**
     * Get all the upcoming birthdays for employees in the given team.
     *
     * @param Team $team
     * @param Company $company
     * @return array
     */
    public static function birthdays(Team $team, Company $company): array
    {
        $employees = $team->employees()
            ->whereNotNull('employees.birthdate')
            ->where('employees.locked', false)
            ->get();

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
                        'company' => $company,
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
     * Get the current morale of the team, according to:
     * - yesterday
     * - last week
     * - last month.
     *
     * @param Team $team
     * @param Employee $loggedEmployee
     * @return array
     */
    public static function morale(Team $team, Employee $loggedEmployee): array
    {
        // yesterday
        $yesterday = Carbon::now($loggedEmployee->timezone)->yesterday();
        $moraleOfYesterday = MoraleTeamHistory::where('team_id', $team->id)
            ->whereDate('created_at', $yesterday)
            ->first();

        // last week
        $mondayOfLastWeek = Carbon::now($loggedEmployee->timezone)->startOfWeek()->subDays(7);
        $moraleOfLastWeek = MoraleTeamHistory::where('team_id', $team->id)
            ->whereBetween('created_at', [
                $mondayOfLastWeek->toDateString().' 00:00:00',
                $mondayOfLastWeek->copy()->addDays(7)->toDateString().' 23:59:59',
            ])
            ->avg('average');

        // last month
        $mondayOfLastMonth = Carbon::now($loggedEmployee->timezone)->subMonth()->startOfMonth();
        $moraleOfLastMonth = MoraleTeamHistory::where('team_id', $team->id)
            ->whereBetween('created_at', [
                $mondayOfLastMonth->toDateString().' 00:00:00',
                $mondayOfLastMonth->copy()->endOfMonth()->toDateString().' 23:59:59',
            ])
            ->avg('average');

        return [
            'yesterday' => [
                'average' => $moraleOfYesterday ? round($moraleOfYesterday->average, 3) : null,
                'percent' => $moraleOfYesterday ? round($moraleOfYesterday->average * 100 / 3, 0) : null,
                'emotion' => $moraleOfYesterday ? self::emotion(round($moraleOfYesterday->average * 100 / 3, 0)) : null,
            ],
            'last_week' => [
                'average' => $moraleOfLastWeek ? round($moraleOfLastWeek, 3) : null,
                'percent' => $moraleOfLastWeek ? round($moraleOfLastWeek * 100 / 3, 0) : null,
                'emotion' => $moraleOfLastWeek ? self::emotion(round($moraleOfLastWeek * 100 / 3, 0)) : null,
            ],
            'last_month' => [
                'average' => $moraleOfLastMonth ? round($moraleOfLastMonth, 3) : null,
                'percent' => $moraleOfLastMonth ? round($moraleOfLastMonth * 100 / 3, 0) : null,
                'emotion' => $moraleOfLastMonth ? self::emotion(round($moraleOfLastMonth * 100 / 3, 0)) : null,
            ],
        ];
    }

    /**
     * Calculates the emotion based on the morale represented in percent.
     */
    private static function emotion(float $percent): string
    {
        $emotion = '';

        switch (true) {
            case $percent <= 30:
                $emotion = 'sad';
                break;

            case $percent > 30 && $percent <= 70:
                $emotion = 'average';
                break;

            case $percent >= 70:
                $emotion = 'happy';
                break;

            default:
                $emotion = 'none';
                break;
        }

        return $emotion;
    }

    /**
     * Get all hires who will start next week.
     *
     * @param Team $team
     * @param Company $company
     * @return Collection
     */
    public static function newHiresNextWeek(Team $team, Company $company): Collection
    {
        $now = Carbon::now();
        return $team->employees()
            ->notLocked()
            ->whereNotNull('hired_at')
            ->whereDate('hired_at', '>=', $now->copy()->addWeek()->startOfWeek(Carbon::MONDAY))
            ->whereDate('hired_at', '<=', $now->copy()->addWeek()->endOfWeek(Carbon::SUNDAY))
            ->with('position')
            ->orderBy('hired_at', 'asc')
            ->get()
            ->map(function ($employee) use ($company) {
                $date = $employee->hired_at;
                $position = $employee->position;

                if ($position) {
                    $dateString = trans('dashboard.team_upcoming_hires_with_position', [
                        'date' => DateHelper::formatDayAndMonthInParenthesis($date),
                        'position' => $position->title,
                    ]);
                } else {
                    $dateString = trans('dashboard.team_upcoming_hires', [
                        'date' => DateHelper::formatDayAndMonthInParenthesis($date),
                    ]);
                }

                return [
                    'id' => $employee->id,
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $employee->id,
                    ]),
                    'name' => $employee->name,
                    'avatar' => ImageHelper::getAvatar($employee, 35),
                    'hired_at' => $dateString,
                ];
            });
    }
}
