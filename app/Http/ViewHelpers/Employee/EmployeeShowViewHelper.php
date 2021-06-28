<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\TimeHelper;
use App\Helpers\ImageHelper;
use App\Helpers\MoneyHelper;
use App\Models\User\Pronoun;
use App\Helpers\StringHelper;
use App\Helpers\BirthdayHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Helpers\WorkFromHomeHelper;
use App\Models\Company\ECoffeeMatch;

class EmployeeShowViewHelper
{
    /**
     * Information about the employee.
     * The information that is given depends on the permissions array that
     * indicates if the logged employee can see info about the employee.
     *
     * @param Employee $employee
     * @param array $permissions
     * @param Employee $loggedEmployee
     * @return array
     */
    public static function informationAboutEmployee(Employee $employee, array $permissions, Employee $loggedEmployee): array
    {
        $address = $employee->getCurrentAddress();
        $company = $employee->company;
        $teams = $employee->teams;
        $rate = $employee->consultantRates()->where('active', true)->first();

        return [
            'id' => $employee->id,
            'name' => $employee->name,
            'first_name' => $employee->first_name,
            'last_name' => $employee->last_name,
            'avatar' => ImageHelper::getAvatar($employee, 300),
            'email' => $employee->email,
            'phone' => $employee->phone_number,
            'twitter_handle' => $employee->twitter_handle,
            'slack_handle' => $employee->slack_handle,
            'locked' => $employee->locked,
            'holidays' => $employee->getHolidaysInformation(),
            'birthdate' => (! $employee->birthdate) ? null :
                ($permissions['can_see_full_birthdate'] ? [
                    'date' => DateHelper::formatDate($employee->birthdate),
                    'age' => BirthdayHelper::age($employee->birthdate, $loggedEmployee->timezone),
                ] : [
                    'date' => DateHelper::formatMonthAndDay($employee->birthdate),
                ]),
            'hired_at' => (! $employee->hired_at) ? null : [
                'full' => DateHelper::formatDate($employee->hired_at),
                'year' => $employee->hired_at->year,
                'month' => $employee->hired_at->month,
                'day' => $employee->hired_at->day,
                'percent' => self::hiredAfterEmployee($employee, $loggedEmployee->company),
            ],
            'contract_renewed_at' => (! $employee->contract_renewed_at) ? null :
                ($permissions['can_see_contract_renewal_date'] ? [
                    'date' => DateHelper::formatDate($employee->contract_renewed_at),
                ] : null),
            'contract_rate' => (! $rate) ? null :
                ($permissions['can_see_contract_renewal_date'] ? [
                    'rate' => $rate->rate,
                    'currency' => $company->currency,
                ] : null),
            'raw_description' => $employee->description,
            'parsed_description' => is_null($employee->description) ? null : StringHelper::parse($employee->description),
            'address' => is_null($address) ? null : [
                'sentence' => $permissions['can_see_complete_address'] ? $address->getCompleteAddress() : $address->getPartialAddress(),
                'openstreetmap_url' => $address->getMapUrl($permissions['can_see_complete_address']),
            ],
            'position' => (! $employee->position) ? null : [
                'id' => $employee->position->id,
                'title' => $employee->position->title,
            ],
            'pronoun' => (! $employee->pronoun) ? null : [
                'id' => $employee->pronoun->id,
                'label' => $employee->pronoun->label,
            ],
            'user' => (! $employee->user) ? null : [
                'id' => $employee->user->id,
            ],
            'status' => (! $employee->status) ? null : [
                'id' => $employee->status->id,
                'name' => $employee->status->name,
                'type' => $employee->status->type,
            ],
            'teams' => ($teams->count() == 0) ? null : self::teams($teams, $company),
            'url' => [
                'audit_log' => route('employee.show.logs', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'edit' => route('employee.show.edit', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'edit_address' => route('employee.show.edit.address', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'edit_contract' => route('employee.show.edit.contract', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'lock' => route('account.lock', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'delete' => route('account.delete', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ],
        ];
    }

    /**
     * Collection containing all the managers of this employee.
     *
     * @param Employee $employee
     * @return Collection
     */
    public static function managers(Employee $employee): Collection
    {
        $managers = $employee->managers;
        $managersOfEmployee = collect([]);
        foreach ($managers as $manager) {
            $manager = $manager->manager;

            if ($manager->locked) {
                continue;
            }

            $managersOfEmployee->push([
                'id' => $manager->id,
                'name' => $manager->name,
                'avatar' => ImageHelper::getAvatar($manager, 35),
                'position' => (! $manager->position) ? null : [
                    'id' => $manager->position->id,
                    'title' => $manager->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $employee->company,
                    'employee' => $manager,
                ]),
            ]);
        }

        return $managersOfEmployee;
    }

    /**
     * Collection containing all the direct reports of this employee.
     *
     * @param Employee $employee
     * @return Collection
     */
    public static function directReports(Employee $employee): Collection
    {
        $directReports = $employee->directReports()->with('directReport.position')->get();
        $directReportsOfEmployee = collect([]);
        foreach ($directReports as $directReport) {
            $directReport = $directReport->directReport;

            if ($directReport->locked) {
                continue;
            }

            $directReportsOfEmployee->push([
                'id' => $directReport->id,
                'name' => $directReport->name,
                'avatar' => ImageHelper::getAvatar($directReport, 35),
                'position' => (! $directReport->position) ? null : [
                    'id' => $directReport->position->id,
                    'title' => $directReport->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $employee->company,
                    'employee' => $directReport,
                ]),
            ]);
        }

        return $directReportsOfEmployee;
    }

    /**
     * Array containing information about the number of times the employee has
     * been working from home this year.
     *
     * @param Employee $employee
     * @return array
     */
    public static function workFromHomeStats(Employee $employee): array
    {
        $now = Carbon::now();
        $currentYear = $now->year;
        $workFromHomes = $employee->workFromHomes()->whereYear('date', (string) $currentYear)->count();

        return [
            'feature_enabled' => $employee->company->work_from_home_enabled,
            'work_from_home_today' => WorkFromHomeHelper::hasWorkedFromHomeOnDate($employee, $now),
            'number_times_this_year' => $workFromHomes,
            'url' => route('employee.work.workfromhome', [
                'company' => $employee->company,
                'employee' => $employee,
            ]),
        ];
    }

    /**
     * Array containing information about the questions answered by the
     * employee.
     *
     * @param Employee $employee
     * @return Collection
     */
    public static function questions(Employee $employee): Collection
    {
        $answers = $employee->answers()->with('question')->get();

        $questionsCollection = collect([]);
        foreach ($answers as $answer) {
            $question = $answer->question;

            $questionsCollection->push([
                'id' => $question->id,
                'title' => $question->title,
                'url' => route('company.questions.show', [
                    'company' => $employee->company,
                    'question' => $question,
                ]),
                'answer' => [
                    'body' => $answer->body,
                ],
            ]);
        }

        return $questionsCollection;
    }

    /**
     * Array containing information about the teams.
     *
     * @param Company $company
     * @param Collection $teams
     * @return Collection
     */
    public static function teams(Collection $teams, Company $company): Collection
    {
        $teamsCollection = collect([]);
        foreach ($teams as $team) {
            $teamsCollection->push([
                'id' => $team->id,
                'name' => $team->name,
                'team_leader' => is_null($team->leader) ? null : [
                    'id' => $team->leader->id,
                ],
                'url' => route('team.show', [
                    'company' => $company,
                    'team' => $team,
                ]),
            ]);
        }

        return $teamsCollection;
    }

    /**
     * Array containing information about the hardware associated with the
     * employee.
     *
     * @param Employee $employee
     * @param array $permissions
     * @return Collection|null
     */
    public static function hardware(Employee $employee, array $permissions): ?Collection
    {
        if (! $permissions['can_see_hardware']) {
            return null;
        }

        $hardware = $employee->hardware;

        $hardwareCollection = collect([]);
        foreach ($hardware as $item) {
            $hardwareCollection->push([
                'id' => $item->id,
                'name' => $item->name,
                'serial_number' => $item->serial_number,
            ]);
        }

        return $hardwareCollection;
    }

    /**
     * Array containing information about the recent ships associated with the
     * employee.
     *
     * @param Employee $employee
     * @return Collection
     */
    public static function recentShips(Employee $employee): Collection
    {
        $ships = $employee->ships;

        $shipsCollection = collect([]);
        foreach ($ships as $ship) {
            $employees = $ship->employees;
            $employeeCollection = collect([]);
            foreach ($employees as $employeeImpacted) {
                if ($employee->id == $employeeImpacted->id) {
                    continue;
                }

                $employeeCollection->push([
                    'id' => $employeeImpacted->id,
                    'name' => $employeeImpacted->name,
                    'avatar' => ImageHelper::getAvatar($employeeImpacted, 17),
                    'url' => route('employees.show', [
                        'company' => $employee->company,
                        'employee' => $employeeImpacted,
                    ]),
                ]);
            }

            $shipsCollection->push([
                'id' => $ship->id,
                'title' => $ship->title,
                'description' => $ship->description,
                'employees' => ($employeeCollection->count() > 0) ? $employeeCollection->all() : null,
                'url' => route('ships.show', [
                    'company' => $employee->company,
                    'team' => $ship->team,
                    'ship' => $ship->id,
                ]),
            ]);
        }

        return $shipsCollection;
    }

    /**
     * Array containing information about the skills associated with the
     * employee.
     *
     * @param Employee $employee
     * @return Collection
     */
    public static function skills(Employee $employee): Collection
    {
        $skills = $employee->skills;

        $skillsCollection = collect([]);
        foreach ($skills as $skill) {
            $skillsCollection->push([
                'id' => $skill->id,
                'name' => $skill->name,
                'url' => route('company.skills.show', [
                    'company' => $skill->company_id,
                    'skill' => $skill->id,
                ]),
            ]);
        }

        return $skillsCollection;
    }

    /**
     * Get the information about the expenses associated with the
     * employee.
     * On the employee profile page, we only see expenses logged in the last
     * 30 days.
     *
     * @param Employee $employee
     * @param array $permissions
     * @param Employee $loggedEmployee
     * @return array|null
     */
    public static function expenses(Employee $employee, array $permissions, Employee $loggedEmployee): ?array
    {
        if (! $permissions['can_see_expenses']) {
            return null;
        }

        $expenses = $employee->expenses;

        // filter out expenses not in the last 30 days
        $latestExpenses = $expenses->filter(function ($expense) {
            return $expense->created_at->greaterThan(Carbon::now()->subDays(30));
        });

        $expensesCollection = collect([]);
        foreach ($latestExpenses as $expense) {
            $expensesCollection->push([
                'id' => $expense->id,
                'title' => $expense->title,
                'amount' => MoneyHelper::format($expense->amount, $expense->currency),
                'status' => $expense->status,
                'expensed_at' => DateHelper::formatDate($expense->expensed_at, $loggedEmployee->timezone),
                'converted_amount' => $expense->converted_amount ?
                    MoneyHelper::format($expense->converted_amount, $expense->converted_to_currency) :
                    null,
                'url' => route('employee.administration.expenses.show', [
                    'company' => $employee->company,
                    'employee' => $employee,
                    'expense' => $expense,
                ]),
            ]);
        }

        return [
            'url' => route('employee.administration.expenses.index', [
                'company' => $employee->company,
                'employee' => $employee,
            ]),
            'expenses' => $expensesCollection,
            'hasMorePastExpenses' => $expenses->count() - $latestExpenses->count() != 0,
            'totalPastExpenses' => $expenses->count() - $latestExpenses->count(),
        ];
    }

    /**
     * Array containing information about the latest one on ones associated with
     * the employee.
     *
     * @param Employee $employee
     * @param array $permissions
     * @param Employee $loggedEmployee
     * @return array|null
     */
    public static function oneOnOnes(Employee $employee, array $permissions, Employee $loggedEmployee): ?array
    {
        if (! $permissions['can_see_one_on_one_with_manager']) {
            return null;
        }

        $oneOnOnes = $employee->oneOnOneEntriesAsEmployee()
            ->with('manager')
            ->orderBy('happened_at', 'desc')->take(3)->get();

        $company = $employee->company;

        $collection = collect([]);
        foreach ($oneOnOnes as $oneOnOne) {
            $collection->push([
                'id' => $oneOnOne->id,
                'happened_at' => DateHelper::formatDate($oneOnOne->happened_at, $loggedEmployee->timezone),
                'manager' => [
                    'id' => $oneOnOne->manager->id,
                    'name' => $oneOnOne->manager->name,
                    'avatar' => ImageHelper::getAvatar($oneOnOne->manager, 18),
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $oneOnOne->manager,
                    ]),
                ],
                'url' => route('employees.show.performance.oneonones.show', [
                    'company' => $company,
                    'employee' => $employee,
                    'oneonone' => $oneOnOne,
                ]),
            ]);
        }

        return [
            'entries' => $collection,
            'view_all_url' => route('employees.show.performance.oneonones.index', [
                'company' => $company,
                'employee' => $employee,
            ]),
        ];
    }

    /**
     * Get the employee statuses for the given company.
     *
     * @param Company $company
     * @return Collection
     */
    public static function employeeStatuses(Company $company): Collection
    {
        $statuses = $company->employeeStatuses()->get();

        $statusCollection = collect([]);
        foreach ($statuses as $status) {
            $statusCollection->push([
                'id' => $status->id,
                'name' => $status->name,
            ]);
        }

        return $statusCollection;
    }

    /**
     * Array containing information about the latest timesheets logged by the
     * employee.
     *
     * @param Employee $employee
     * @param array $permissions
     * @return array|null
     */
    public static function timesheets(Employee $employee, array $permissions): ?array
    {
        if (! $permissions['can_see_timesheets']) {
            return null;
        }

        $timesheets = $employee->timesheets()
            ->whereIn('timesheets.status', [Timesheet::APPROVED, Timesheet::REJECTED])
            ->orderBy('timesheets.started_at', 'desc')
            ->take(3)
            ->get();

        $company = $employee->company;

        $timesheetCollection = collect([]);
        foreach ($timesheets as $timesheet) {
            $totalWorkedInMinutes = DB::table('time_tracking_entries')
                ->where('timesheet_id', $timesheet->id)
                ->sum('duration');

            $arrayOfTime = TimeHelper::convertToHoursAndMinutes($totalWorkedInMinutes);

            $timesheetCollection->push([
                'id' => $timesheet->id,
                'started_at' => DateHelper::formatDate($timesheet->started_at),
                'ended_at' => DateHelper::formatDate($timesheet->ended_at),
                'duration' => trans('dashboard.manager_timesheet_approval_duration', [
                    'hours' => $arrayOfTime['hours'],
                    'minutes' => $arrayOfTime['minutes'],
                ]),
                'status' => $timesheet->status,
                'url' => route('employee.timesheets.show', [
                    'company' => $company,
                    'employee' => $employee,
                    'timesheet' => $timesheet,
                ]),
            ]);
        }

        return [
            'entries' => $timesheetCollection,
            'view_all_url' => route('employee.timesheets.index', [
                'company' => $company,
                'employee' => $employee,
            ]),
        ];
    }

    /**
     * Array containing information about all the pronouns used in the company.
     *
     * @return Collection|null
     */
    public static function pronouns(): ?Collection
    {
        $pronounCollection = collect([]);
        $pronouns = Pronoun::orderBy('id', 'asc')->get();

        foreach ($pronouns as $pronoun) {
            $pronounCollection->push([
                'id' => $pronoun->id,
                'label' => $pronoun->label,
            ]);
        }

        return $pronounCollection;
    }

    /**
     * Array containing information about all the positions used in the company.
     *
     * @param Company $company
     * @return Collection|null
     */
    public static function positions(Company $company): ?Collection
    {
        $positionCollection = collect([]);
        $positions = $company->positions;

        foreach ($positions as $position) {
            $positionCollection->push([
                'id' => $position->id,
                'title' => $position->title,
            ]);
        }

        return $positionCollection;
    }

    /**
     * List all the eCoffees the employee participated to.
     *
     * @param Employee $employee
     * @param Company $company
     * @return array|null
     */
    public static function eCoffees(Employee $employee, Company $company): ?array
    {
        $matches = ECoffeeMatch::where(function ($query) use ($employee) {
            $query->where('employee_id', $employee->id)
                ->orWhere('with_employee_id', $employee->id);
        })->orderBy('id', 'desc')
            ->with('eCoffee')
            ->take(3)->get();

        $eCoffeeCollection = collect([]);
        foreach ($matches as $match) {
            if ($employee->id == $match->with_employee_id) {
                $withEmployee = $match->employee;
            } else {
                $withEmployee = $match->employeeMatchedWith;
            }

            $eCoffeeCollection->push([
                'id' => $match->id,
                'ecoffee' => [
                    'started_at' => DateHelper::formatDate($match->eCoffee->created_at),
                    'ended_at' => DateHelper::formatDate($match->eCoffee->created_at->endOfWeek(Carbon::SUNDAY)),
                ],
                'with_employee' => [
                    'id' => $withEmployee->id,
                    'name' => $withEmployee->name,
                    'first_name' => $withEmployee->first_name,
                    'avatar' => ImageHelper::getAvatar($withEmployee, 35),
                    'position' => $withEmployee->position ? $withEmployee->position->title : null,
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $withEmployee,
                    ]),
                ],
            ]);
        }

        return [
            'view_all_url' => route('employees.ecoffees.index', [
                'company' => $company,
                'employee' => $employee,
            ]),
            'eCoffees' => $eCoffeeCollection,
        ];
    }

    /**
     * Get the percent of employees who have been hired after the given employee.
     *
     * @param Employee $employee
     * @param Company $company
     * @return ?int
     */
    public static function hiredAfterEmployee(Employee $employee, Company $company): ?int
    {
        $totalNumberOfEmployees = $company->employees()->count();
        $employeesHiredAfterMe = $company->employees()
            ->whereDate('hired_at', '>', $employee->hired_at)
            ->where('employees.id', '!=', $employee->id)
            ->count();

        if ($employeesHiredAfterMe == 0) {
            return 0;
        }

        $percent = round($employeesHiredAfterMe * 100 / $totalNumberOfEmployees);

        return $percent;
    }

    /**
     * Get the list of all positions the employee ever had in the company.
     *
     * @param Employee $employee
     * @param Company $company
     * @return Collection
     */
    public static function employeeCurrentAndPastPositions(Employee $employee, Company $company): Collection
    {
        $positions = $employee->positionHistoryEntries()
            ->orderBy('started_at', 'desc')
            ->get();

        $positionCollection = collect();
        foreach ($positions as $entry) {
            $positionCollection->push([
                'id' => $entry->id,
                'position' => $entry->position->title,
                'started_at' => DateHelper::formatMonthAndYear($entry->started_at),
                'ended_at' => $entry->ended_at ? DateHelper::formatMonthAndYear($entry->ended_at) : null,
            ]);
        }

        return $positionCollection;
    }

    /**
     * Array containing information about the software associated with the
     * employee.
     *
     * @param Employee $employee
     * @param array $permissions
     * @return Collection|null
     */
    public static function softwares(Employee $employee, array $permissions): ?Collection
    {
        if (! $permissions['can_see_software']) {
            return null;
        }

        return $employee->softwares->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        });
    }
}
