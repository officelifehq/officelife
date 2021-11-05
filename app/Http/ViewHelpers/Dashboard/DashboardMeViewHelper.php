<?php

namespace App\Http\ViewHelpers\Dashboard;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\MoneyHelper;
use App\Helpers\QuestionHelper;
use App\Models\Company\Company;
use App\Models\Company\ECoffee;
use App\Models\Company\Expense;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use App\Helpers\WorkFromHomeHelper;
use Money\Currencies\ISOCurrencies;
use App\Models\Company\ECoffeeMatch;
use App\Models\Company\OneOnOneEntry;
use App\Models\Company\EmployeeStatus;
use App\Models\Company\AskMeAnythingSession;
use App\Models\Company\CandidateStageParticipant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
                    'avatar' => ImageHelper::getAvatar($answer->employee, 22),
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
     * Get all the currencies used in this OfficeLife instance.
     *
     * @return Collection|null
     */
    public static function currencies(): ?Collection
    {
        $currencyCollection = collect([]);
        $currencies = new ISOCurrencies();
        foreach ($currencies as $currency) {
            $currencyCollection->push([
                'value' => $currency->getCode(),
                'label' => $currency->getCode(),
            ]);
        }

        return $currencyCollection;
    }

    /**
     * Get all the in progress expenses for this employee.
     *
     * @param Employee $employee
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
                'expensed_at' => DateHelper::formatDate($expense->expensed_at, $employee->timezone),
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
     * @param Employee $employee
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
                'avatar' => ImageHelper::getAvatar($manager, 35),
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
                'contract_renewed_at' => DateHelper::formatDate($employee->contract_renewed_at, $employee->timezone),
                'number_of_days' => $employee->contract_renewed_at->diffInDays($now),
                'late' => true,
            ];
        }

        return [
            'contract_renewed_at' => DateHelper::formatDate($employee->contract_renewed_at, $employee->timezone),
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

        try {
            $match = ECoffeeMatch::where('e_coffee_id', $latestECoffee->id)
                ->where(function ($query) use ($employee) {
                    $query->where('employee_id', $employee->id)
                        ->orWhere('with_employee_id', $employee->id);
                })
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }

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
                'avatar' => ImageHelper::getAvatar($employee),
            ],
            'other_employee' => [
                'id' => $otherEmployee->id,
                'name' => $otherEmployee->name,
                'first_name' => $otherEmployee->first_name,
                'avatar' => ImageHelper::getAvatar($otherEmployee, 55),
                'position' => $otherEmployee->position ? $otherEmployee->position->title : null,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $otherEmployee,
                ]),
                'teams' => $teamsCollection->count() == 0 ? null : $teamsCollection,
            ],
        ];
    }

    /**
     * Get the projects the employee participates in.
     *
     * @param Employee $employee
     * @param Company $company
     * @return Collection|null
     */
    public static function projects(Employee $employee, Company $company): ?Collection
    {
        $openProjects = $employee->projects()
            ->where('status', Project::STARTED)
            ->orWhere('status', Project::PAUSED)
            ->with('employees')
            ->get();

        $projectsCollection = collect([]);
        foreach ($openProjects as $project) {
            $members = $project->employees()
                ->inRandomOrder()
                ->take(3)
                ->get();

            $totalMembersCount = $project->employees()->count();
            $totalMembersCount = $totalMembersCount - $members->count();

            $membersCollection = collect([]);
            foreach ($members as $member) {
                $membersCollection->push([
                    'id' => $member->id,
                    'avatar' => ImageHelper::getAvatar($member, 32),
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $member,
                    ]),
                ]);
            }

            $projectsCollection->push([
                'id' => $project->id,
                'name' => $project->name,
                'code' => $project->code,
                'url' => route('projects.show', [
                    'company' => $company,
                    'project' => $project,
                ]),
                'preview_members' => $membersCollection,
                'remaining_members_count' => $totalMembersCount,
            ]);
        }

        return $projectsCollection;
    }

    /**
     * Get the company currency.
     *
     * @param Company $company
     * @return array|null
     */
    public static function companyCurrency(Company $company): ?array
    {
        return [
            'id' => $company->currency,
            'code' => $company->currency,
        ];
    }

    /**
     * Get the information about worklogs.
     *
     * @param Employee $employee
     * @return array|null
     */
    public static function worklogs(Employee $employee): ?array
    {
        return [
            'has_already_logged_a_worklog_today' => $employee->hasAlreadyLoggedWorklogToday(),
            'has_worklog_history' => $employee->worklogs()->count() > 0 ? true : false,
        ];
    }

    /**
     * Get the information about the morale.
     *
     * @param Employee $employee
     * @return array|null
     */
    public static function morale(Employee $employee): ?array
    {
        return [
            'has_logged_morale_today' => $employee->hasAlreadyLoggedMoraleToday(),
        ];
    }

    /**
     * Get the information about working from home.
     *
     * @param Employee $employee
     * @return array|null
     */
    public static function workFromHome(Employee $employee): ?array
    {
        return [
            'feature_enabled' => $employee->company->work_from_home_enabled,
            'has_worked_from_home_today' => WorkFromHomeHelper::hasWorkedFromHomeOnDate($employee, Carbon::now()),
        ];
    }

    /**
     * Get the information about the job openings as a sponsor.
     *
     * @param Company $company
     * @param Employee $employee
     * @return Collection
     */
    public static function jobOpeningsAsSponsor(Company $company, Employee $employee): Collection
    {
        $jobOpenings = $employee->jobOpeningsAsSponsor()
            ->where('fulfilled', false)
            ->orderBy('job_openings.created_at', 'desc')
            ->get();

        $jobsOpeningCollection = collect();
        foreach ($jobOpenings as $jobOpening) {
            $jobsOpeningCollection->push([
                'id' => $jobOpening->id,
                'title' => $jobOpening->title,
                'reference_number' => $jobOpening->reference_number,
                'url' => route('recruiting.openings.show', [
                    'company' => $company,
                    'jobOpening' => $jobOpening,
                ]),
            ]);
        }

        return $jobsOpeningCollection;
    }

    /**
     * Get the information about the job openings as a participant.
     *
     * @param Employee $employee
     * @return Collection
     */
    public static function jobOpeningsAsParticipant(Employee $employee): Collection
    {
        $stages = CandidateStageParticipant::where('participant_id', $employee->id)
            ->where('participated', false)
            ->with('candidateStage')
            ->with('candidateStage.candidate')
            ->with('candidateStage.candidate.jobOpening')
            ->get();

        $jobOpeningsCollection = collect();
        foreach ($stages as $stage) {
            $jobOpening = $stage->candidateStage->candidate->jobOpening;
            if (! $jobOpening->active) {
                continue;
            }

            $jobOpeningsCollection->push([
                'id' => $jobOpening->id,
                'candidate_stage_id' => $stage->candidate_stage_id,
                'title' => $jobOpening->title,
                'participated' => false,
                'candidate' => [
                    'id' => $stage->candidateStage->candidate->id,
                    'name' => $stage->candidateStage->candidate->name,
                ],
            ]);
        }

        return $jobOpeningsCollection;
    }

    /**
     * Get the information about the current active ask me anything session, if
     * it exists.
     *
     * @param Company $company
     * @param Employee $employee
     * @return array
     */
    public static function activeAskMeAnythingSession(Company $company, Employee $employee): ?array
    {
        $session = AskMeAnythingSession::where('company_id', $company->id)
            ->where('active', true)
            ->first();

        if (! $session) {
            return null;
        }

        $questionsAskedByEmployee = $session->questions()
            ->where('employee_id', $employee->id)
            ->count();

        $questionsInTotal = $session->questions()->count();

        return [
            'id' => $session->id,
            'active' => $session->active,
            'theme' => $session->theme,
            'happened_at' => DateHelper::formatDate($session->happened_at),
            'url_new' => route('dashboard.ama.question.store', [
                'company' => $company->id,
                'session' => $session->id,
            ]),
            'questions_asked_by_employee_count' => $questionsAskedByEmployee,
            'questions_in_total_count' => $questionsInTotal,
        ];
    }
}
