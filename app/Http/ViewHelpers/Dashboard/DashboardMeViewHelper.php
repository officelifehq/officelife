<?php

namespace App\Http\ViewHelpers\Dashboard;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\MoneyHelper;
use App\Helpers\AvatarHelper;
use App\Helpers\QuestionHelper;
use App\Models\Company\Company;
use App\Models\Company\ECoffee;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use Money\Currencies\ISOCurrencies;
use App\Models\Company\ECoffeeMatch;
use App\Models\Company\OneOnOneEntry;
use App\Models\Company\EmployeeStatus;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneEntry;

class DashboardMeViewHelper
{
    /**
     * Array containing all the information about the current active question.
     *
     * @param Employee $employee
     * @return array|null
     */
    public static function question(Employee $employee): ?array
    {
        // get active question
        $question = $employee->company->questions()->with('answers')->with('answers.employee')->active()->first();

        // if no active question
        if (! $question) {
            return null;
        }

        $answerByEmployee = QuestionHelper::getAnswer($question, $employee);

        // collection of all employee answers
        $allAnswers = $question->answers;
        $answersCollection = collect([]);
        foreach ($allAnswers->take(3) as $answer) {
            $answersCollection->push([
                'id' => $answer->id,
                'body' => $answer->body,
                'employee' => [
                    'id' => $answer->employee->id,
                    'name' => $answer->employee->name,
                    'avatar' => AvatarHelper::getImage($answer->employee, 22),
                ],
            ]);
        }

        $response = [
            'id' => $question->id,
            'title' => $question->title,
            'number_of_answers' => $allAnswers->count(),
            'answers' => $answersCollection,
            'employee_has_answered' => (bool) $answerByEmployee,
            'answer_by_employee' => $answerByEmployee ? [
                'body' => $answerByEmployee->body,
            ] : null,
            'url' => route('company.questions.show', [
                'company' => $employee->company,
                'question' => $question,
            ]),
        ];

        return $response;
    }

    /**
     * All the tasks of this employee.
     *
     * @param Employee $employee
     * @return Collection|null
     */
    public static function tasks(Employee $employee): ?Collection
    {
        $tasks = $employee->tasks()->inProgress()->get();

        $tasksCollection = collect([]);
        foreach ($tasks as $task) {
            $tasksCollection->push([
                'id' => $task->id,
                'title' => $task->title,
            ]);
        }

        return $tasksCollection;
    }

    /**
     * All the expense categories available in this company.
     *
     * @param Company $company
     * @return Collection|null
     */
    public static function categories(Company $company): ?Collection
    {
        $categories = $company->expenseCategories;

        $categoriesCollection = collect([]);
        foreach ($categories as $category) {
            $categoriesCollection->push([
                'id' => $category->id,
                'name' => $category->name,
            ]);
        }

        return $categoriesCollection;
    }

    /**
     * Get all the currencies used in the instance.
     *
     * @return Collection|null
     */
    public static function currencies(): ?Collection
    {
        $currencyCollection = collect([]);
        $currencies = new ISOCurrencies();
        foreach ($currencies as $currency) {
            $currencyCollection->push([
                'id' =>$currency->getCode(),
                'code' => $currency->getCode(),
            ]);
        }

        return $currencyCollection;
    }

    /**
     * Get all the in progress expenses for this employee.
     *
     * @var Employee
     * @return Collection|null
     */
    public static function expenses(Employee $employee): ?Collection
    {
        $expenses = $employee->expenses()
            ->where('expenses.status', '!=', Expense::ACCEPTED)
            ->where('expenses.status', '!=', Expense::CREATED)
            ->where('expenses.status', '!=', Expense::REJECTED_BY_MANAGER)
            ->where('expenses.status', '!=', Expense::REJECTED_BY_ACCOUNTING)
            ->with('category')
            ->latest()
            ->get();

        $expensesCollection = collect([]);
        foreach ($expenses as $expense) {
            $expensesCollection->push([
                'id' => $expense->id,
                'title' => $expense->title,
                'amount' => MoneyHelper::format($expense->amount, $expense->currency),
                'status' => $expense->status,
                'category' => ($expense->category) ? $expense->category->name : null,
                'expensed_at' => DateHelper::formatDate($expense->expensed_at),
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

        return $expensesCollection;
    }

    /**
     * Get all the Rate Your Manager survey answers that need to be answered, if
     * they exist.
     *
     * @var Employee
     * @return Collection|null
     */
    public static function rateYourManagerAnswers(Employee $employee): ?Collection
    {
        // is there currently an active RateYourManager survey?
        $answers = $employee->rateYourManagerAnswers()
            ->where('active', true)
            ->whereNull('rating')
            ->with('entry')
            ->with('entry.manager')
            ->get();

        $answersCollection = collect([]);
        foreach ($answers as $answer) {
            $answersCollection->push([
                'id' => $answer->id,
                'manager_name' => $answer->entry->manager->name,
                'deadline' => DateHelper::hoursOrDaysLeft($answer->entry->valid_until_at),
            ]);
        }

        return $answersCollection;
    }

    /**
     * Get the one on ones with the manager(s) if they exist.
     *
     * @return Collection
     */
    public static function oneOnOnes(Employee $employee): Collection
    {
        $managers = $employee->getListOfManagers();
        $company = $employee->company;
        $managersCollection = collect([]);
        $now = Carbon::now();

        foreach ($managers as $manager) {
            // for each manager, we need to check if there is an active one on
            // one entry, if not, we need to create one
            $entry = OneOnOneEntry::where('employee_id', $employee->id)
                ->where('manager_id', $manager->id)
                ->where('happened', false)
                ->latest()
                ->first();

            if (! $entry) {
                // there is no active entry, we need to create one
                $entry = (new CreateOneOnOneEntry)->execute([
                    'company_id' => $company->id,
                    'author_id' => $employee->id,
                    'manager_id' => $manager->id,
                    'employee_id' => $employee->id,
                    'date' => $now->format('Y-m-d'),
                ]);
            }

            $managersCollection->push([
                'id' => $manager->id,
                'name' => $manager->name,
                'avatar' => AvatarHelper::getImage($manager, 35),
                'position' => (! $manager->position) ? null : $manager->position->title,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $manager,
                ]),
                'entry' => [
                    'id' => $entry->id,
                    'url' => route('dashboard.oneonones.show', [
                        'company' => $company,
                        'entry' => $entry,
                    ]),
                ],
            ]);
        }

        return $managersCollection;
    }

    /**
     * Get the information about contract renewal, if the employee is external,
     * and if the contract is due in the next 3 months or less.
     *
     * @param Employee $employee
     * @return array|null
     */
    public static function contractRenewal(Employee $employee): ?array
    {
        if (! $employee->status) {
            return null;
        }

        if ($employee->status->type == EmployeeStatus::INTERNAL) {
            return null;
        }

        if (! $employee->contract_renewed_at) {
            return null;
        }

        $now = Carbon::now();
        $dateInOneMonth = $now->copy()->addMonths(1);

        if ($employee->contract_renewed_at->isAfter($dateInOneMonth)) {
            return null;
        }

        if ($employee->contract_renewed_at->isBefore($now)) {
            return [
                'contract_renewed_at' => DateHelper::formatDate($employee->contract_renewed_at),
                'number_of_days' => $employee->contract_renewed_at->diffInDays($now),
                'late' => true,
            ];
        }

        return [
            'contract_renewed_at' => DateHelper::formatDate($employee->contract_renewed_at),
            'number_of_days' => $employee->contract_renewed_at->diffInDays($now),
            'late' => false,
        ];
    }

    /**
     * Get the latest match for the eCoffee program, if it’s enabled for the
     * company.
     *
     * @param Employee $employee
     * @param Company $company
     * @return array|null
     */
    public static function eCoffee(Employee $employee, Company $company): ?array
    {
        if (! $company->e_coffee_enabled) {
            return null;
        }

        $latestECoffee = $company->eCoffees()->orderBy('id', 'desc')->first();

        if (! $latestECoffee) {
            return null;
        }

        $match = ECoffeeMatch::where('e_coffee_id', $latestECoffee->id)
            ->where(function ($query) use ($employee) {
                $query->where('employee_id', $employee->id)
                    ->orWhere('with_employee_id', $employee->id);
            })
            ->firstOrFail();

        if ($match->employee_id == $employee->id) {
            $otherEmployee = $match->employeeMatchedWith;
        } else {
            $otherEmployee = $match->employee;
        }

        $teams = $otherEmployee->teams;
        $teamsCollection = collect([]);
        foreach ($teams as $team) {
            $teamsCollection->push([
                'id' => $team->id,
                'name' => $team->name,
                'url' => route('team.show', [
                    'company' => $company,
                    'team' => $team,
                ]),
            ]);
        }

        return [
            'id' => $match->id,
            'e_coffee_id' => $latestECoffee->id,
            'happened' => $match->happened,
            'employee' => [
                'avatar' => AvatarHelper::getImage($employee),
            ],
            'other_employee' => [
                'id' => $otherEmployee->id,
                'name' => $otherEmployee->name,
                'first_name' => $otherEmployee->first_name,
                'avatar' => AvatarHelper::getImage($otherEmployee, 55),
                'position' => $otherEmployee->position ? $otherEmployee->position->title : null,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $otherEmployee,
                ]),
                'teams' => $teamsCollection->count() == 0 ? null : $teamsCollection,
            ],
        ];
    }
}
