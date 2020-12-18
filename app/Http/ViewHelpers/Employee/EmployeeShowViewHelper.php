<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\MoneyHelper;
use App\Helpers\StringHelper;
use App\Helpers\WorklogHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use App\Helpers\WorkFromHomeHelper;
use App\Models\Company\EmployeeStatus;

class EmployeeShowViewHelper
{
    /**
     * Information about the employee.
     * The information that is given depends on the permissions array that
     * indicates if the logged employee can see info about the employee.
     *
     * @param Employee $employee
     * @param array $permissions
     * @return array
     */
    public static function informationAboutEmployee(Employee $employee, array $permissions): array
    {
        $address = $employee->getCurrentAddress();
        $company = $employee->company;

        return [
            'id' => $employee->id,
            'name' => $employee->name,
            'first_name' => $employee->first_name,
            'last_name' => $employee->last_name,
            'avatar' => $employee->avatar,
            'email' => $employee->email,
            'phone' => $employee->phone_number,
            'twitter_handle' => $employee->twitter_handle,
            'slack_handle' => $employee->slack_handle,
            'locked' => $employee->locked,
            'holidays' => $employee->getHolidaysInformation(),
            'birthdate' => (! $employee->birthdate) ? null :
                ($permissions['can_see_full_birthdate'] ? [
                    'date' => DateHelper::formatDate($employee->birthdate),
                    'age' => Carbon::now()->year - $employee->birthdate->year,
                ] : [
                    'date' => DateHelper::formatMonthAndDay($employee->birthdate),
                ]),
            'hired_at' => (! $employee->hired_at) ? null : [
                'full' => DateHelper::formatDate($employee->hired_at),
                'year' => $employee->hired_at->year,
                'month' => $employee->hired_at->month,
                'day' => $employee->hired_at->day,
            ],
            'contract_renewed_at' => (! $employee->contract_renewed_at) ? null :
                ($permissions['can_see_contract_renewal_date'] ? [
                    'date' => DateHelper::formatDate($employee->contract_renewed_at),
                ] : null),
            'raw_description' => $employee->description,
            'parsed_description' => is_null($employee->description) ? null : StringHelper::parse($employee->description),
            'address' => is_null($address) ? null : [
                'sentence' => $permissions['can_see_complete_address'] ? $address->getCompleteAddress() : $address->getPartialAddress(),
                'openstreetmap_url' => $address->getMapUrl($permissions['can_see_complete_address']),
                'image' => $address->getStaticMapImage(7, 600, 130),
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
            ],
            'url' => [
                'audit_log' => route('employee.show.logs', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'edit' => route('employee.show.edit', [
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
     * Information about what the logged employee can see on the page of the
     * given employee.
     *
     * @param Employee $loggedEmployee
     * @param Employee $employee
     * @return array
     */
    public static function permissions(Employee $loggedEmployee, Employee $employee): array
    {
        $loggedEmployeeIsManager = $loggedEmployee->isManagerOf($employee->id);

        // can the logged employee see the complete birthdate of the employee
        $canSeeFullBirthdate = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeFullBirthdate = true;
        }

        // can the logged employee manage expenses
        $canSeeExpenses = $loggedEmployee->can_manage_expenses;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeExpenses = true;
        }
        if ($loggedEmployeeIsManager) {
            $canSeeExpenses = true;
        }

        // can the logged employee see the work from home history?
        $canSeeWorkFromHomeHistory = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeWorkFromHomeHistory = true;
        }

        // can the logged employee see the work log home history?
        $canSeeWorkLogHistory = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeWorkLogHistory = true;
        }

        // can the logged employee manage hierarchy?
        $canManageHierarchy = $loggedEmployee->permission_level <= 200;

        // can manage position?
        $canManagePosition = $loggedEmployee->permission_level <= 200;

        // can manage teams?
        $canManageTeam = $loggedEmployee->permission_level <= 200;

        // can manage pronouns?
        $canManagePronouns = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canManagePronouns = true;
        }

        // can manage status?
        $canManageStatus = $loggedEmployee->permission_level <= 200;

        // can manage skills?
        $canManageSkills = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canManageSkills = true;
        }

        // can manage description?
        $canManageDescription = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canManageDescription = true;
        }

        // can edit profile?
        $canEditProfile = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canEditProfile = true;
        }

        // can delete profile?
        $canDeleteProfile = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canDeleteProfile = false;
        }

        // can see audit log?
        $canSeeAuditLog = $loggedEmployee->permission_level <= 200;

        // can see complete address?
        $canSeeCompleteAddress = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeCompleteAddress = true;
        }

        // can see one on one with manager
        $canSeeOneOnOneWithManager = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeOneOnOneWithManager = true;
        }
        if ($loggedEmployeeIsManager) {
            $canSeeOneOnOneWithManager = true;
        }

        // can see performance tab?
        $canSeePerformanceTab = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeePerformanceTab = true;
        }
        if ($loggedEmployeeIsManager) {
            $canSeePerformanceTab = true;
        }

        // can see hardware
        $canSeeHardware = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeHardware = true;
        }

        // can see contract renewal date for external employees
        $canSeeContractRenewalDate = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeContractRenewalDate = true;
        }
        if ($loggedEmployeeIsManager) {
            $canSeeContractRenewalDate = true;
        }
        if ($employee->status) {
            if ($employee->status->type == EmployeeStatus::INTERNAL) {
                $canSeeContractRenewalDate = false;
            }
        } else {
            $canSeeContractRenewalDate = false;
        }

        return [
            'can_see_full_birthdate' => $canSeeFullBirthdate,
            'can_manage_hierarchy' => $canManageHierarchy,
            'can_manage_position' => $canManagePosition,
            'can_manage_pronouns' => $canManagePronouns,
            'can_marray' => $canManagePronouns,
            'can_manage_status' => $canManageStatus,
            'can_manage_teams' => $canManageTeam,
            'can_manage_skills' => $canManageSkills,
            'can_manage_description' => $canManageDescription,
            'can_see_expenses' => $canSeeExpenses,
            'can_see_work_from_home_history' => $canSeeWorkFromHomeHistory,
            'can_see_work_log_history' => $canSeeWorkLogHistory,
            'can_see_hardware' => $canSeeHardware,
            'can_edit_profile' => $canEditProfile,
            'can_delete_profile' => $canDeleteProfile,
            'can_see_audit_log' => $canSeeAuditLog,
            'can_see_complete_address' => $canSeeCompleteAddress,
            'can_see_performance_tab' => $canSeePerformanceTab,
            'can_see_one_on_one_with_manager' => $canSeeOneOnOneWithManager,
            'can_see_contract_renewal_date' => $canSeeContractRenewalDate,
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

            $managersOfEmployee->push([
                'id' => $manager->id,
                'name' => $manager->name,
                'avatar' => $manager->avatar,
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

            $directReportsOfEmployee->push([
                'id' => $directReport->id,
                'name' => $directReport->name,
                'avatar' => $directReport->avatar,
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
     * Array containing a collection of all worklogs with the morale and a
     * link to the detailled page of the worklogs.
     *
     * @param Employee $employee
     * @return array
     */
    public static function worklogs(Employee $employee): array
    {
        $worklogs = $employee->worklogs()->latest()->take(7)->get();
        $morales = $employee->morales()->latest()->take(7)->get();
        $worklogsCollection = collect([]);
        $currentDate = Carbon::now();

        // worklogs from Monday to Friday of the current week
        for ($i = 0; $i < 5; $i++) {
            $day = $currentDate->copy()->startOfWeek()->addDays($i);

            $worklog = $worklogs->first(function ($worklog) use ($day) {
                return $worklog->created_at->format('Y-m-d') == $day->format('Y-m-d');
            });

            $morale = $morales->first(function ($morale) use ($day) {
                return $morale->created_at->format('Y-m-d') == $day->format('Y-m-d');
            });

            $worklogsCollection->push(
                WorklogHelper::getDailyInformationForEmployee($day, $worklog, $morale)
            );
        }

        $array = [
            'worklogs_collection' => $worklogsCollection,
            'url' => route('employees.worklogs', [
                'company' => $employee->company,
                'employee' => $employee,
            ]),
        ];

        return $array;
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
        $workFromHomes = $employee->workFromHomes;

        // get all entries in the current year
        $entries = $workFromHomes->filter(function ($entry) {
            return $entry->date->isCurrentYear();
        });

        return [
            'work_from_home_today' => WorkFromHomeHelper::hasWorkedFromHomeOnDate($employee, Carbon::now()),
            'number_times_this_year' => $entries->count(),
            'url' => route('employees.workfromhome', [
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
     * @param Employee $employee
     * @param Collection $teams
     * @return Collection
     */
    public static function teams(Collection $teams, Employee $employee): Collection
    {
        // reduce the number of queries that the foreach loop generates
        // we don't need to iterate over this over and over as it'll be the same
        //for all those companies
        $company = $employee->company;

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
                    'avatar' => $employeeImpacted->avatar,
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
     * Array containing information about the expenses associated with the
     * employee.
     * On the employee profile page, we only see expenses logged in the last
     * 30 days.
     *
     * @param Employee $employee
     * @param array $permissions
     * @return array|null
     */
    public static function expenses(Employee $employee, array $permissions): ?array
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
                'expensed_at' => DateHelper::formatDate($expense->expensed_at),
                'converted_amount' => $expense->converted_amount ?
                    MoneyHelper::format($expense->converted_amount, $expense->converted_to_currency) :
                    null,
                'url' => route('employee.expenses.show', [
                    'company' => $employee->company,
                    'employee' => $employee,
                    'expense' => $expense,
                ]),
            ]);
        }

        return [
            'url' => route('employee.expenses.index', [
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
     * @return array|null
     */
    public static function oneOnOnes(Employee $employee, array $permissions): ?array
    {
        if (! $permissions['can_see_one_on_one_with_manager']) {
            return null;
        }

        $oneOnOnes = $employee->oneOnOneEntriesAsEmployee()
            ->with('manager')
            ->latest()->take(3)->get();

        $company = $employee->company;

        $collection = collect([]);
        foreach ($oneOnOnes as $oneOnOne) {
            $collection->push([
                'id' => $oneOnOne->id,
                'happened_at' => DateHelper::formatDate($oneOnOne->happened_at),
                'manager' => [
                    'id' => $oneOnOne->manager->id,
                    'name' => $oneOnOne->manager->name,
                    'avatar' => $oneOnOne->manager->avatar,
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $oneOnOne->manager,
                    ]),
                ],
                'url' => route('employees.oneonones.show', [
                    'company' => $company,
                    'employee' => $employee,
                    'oneonone' => $oneOnOne,
                ]),
            ]);
        }

        return [
            'entries' => $collection,
            'view_all_url' => route('employees.oneonones.index', [
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
}
