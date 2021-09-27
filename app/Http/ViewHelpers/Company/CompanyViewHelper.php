<?php

namespace App\Http\ViewHelpers\Company;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use OutOfRangeException;
use App\Helpers\DateHelper;
use Illuminate\Support\Str;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Helpers\BirthdayHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Question;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Services\Company\GuessEmployeeGame\CreateGuessEmployeeGame;

class CompanyViewHelper
{
    /**
     * Array containing several statistics for this company.
     *
     * @param Company $company
     * @return array
     */
    public static function information(Company $company): array
    {
        $teams = $company->teams->count();
        $employees = $company->employees()->notLocked()->count();

        return [
            'number_of_teams' => $teams,
            'number_of_employees' => $employees,
            'logo' => $company->logo ? ImageHelper::getImage($company->logo, 75, 75) : null,
            'founded_at' => $company->founded_at ? $company->founded_at->year : null,
        ];
    }

    /**
     * Array containing all the information about the latest questions in the
     * company.
     *
     * @param Company $company
     * @return array
     */
    public static function latestQuestions(Company $company): array
    {
        $questionsCount = DB::table('questions')
            ->where('company_id', $company->id)
            ->count();

        // get the 3 latest questions asked to every employee of the company
        $latestQuestions = DB::table('questions')
            ->join('answers', 'questions.id', '=', 'answers.question_id')
            ->where('company_id', '=', $company->id)
            ->where('questions.active', false)
            ->groupBy('questions.id', 'questions.title')
            ->orderByDesc('questions.id')
            ->limit(3)
            ->select('questions.id', 'questions.title')
            ->selectRaw('count(answers.id) as count')
            ->get();

        // building a collection of questions
        $questionCollection = $latestQuestions->filter(function ($question) {
            return $question->count > 0;
        })->map(function ($question) use ($company) {
            return [
                'id' => $question->id,
                'title' => $question->title,
                'number_of_answers' => $question->count,
                'active' => false,
                'url' => route('company.questions.show', [
                    'company' => $company,
                    'question' => $question->id,
                ]),
            ];
        });

        // get the active question, if it exists
        $activeQuestion = Question::where('company_id', $company->id)
            ->active()
            ->withCount('answers')
            ->first();

        if ($activeQuestion) {
            $activeQuestion = [
                'id' => $activeQuestion->id,
                'title' => $activeQuestion->title,
                'number_of_answers' => (int) $activeQuestion->answers_count,
                'active' => true,
                'url' => route('company.questions.show', [
                    'company' => $company,
                    'question' => $activeQuestion->id,
                ]),
            ];
        }

        return [
            'total_number_of_questions' => $questionsCount,
            'all_questions_url' => route('company.questions.index', [
                'company' => $company->id,
            ]),
            'questions' => $questionCollection,
            'active_question' => $activeQuestion ? $activeQuestion : null,
        ];
    }

    /**
     * Array containing all the information about the birthdays of employees
     * in the current week.
     *
     * @param Company $company
     * @return array
     */
    public static function birthdaysThisWeek(Company $company): array
    {
        $employees = $company->employees()
            ->notLocked()
            ->whereNotNull('birthdate')
            ->get();

        $now = Carbon::now();
        $minDate = $now->copy()->startOfWeek(Carbon::MONDAY);
        $maxDate = $now->copy()->endOfWeek(Carbon::SUNDAY);

        $birthdaysCollection = collect([]);
        foreach ($employees as $employee) {
            $birthdateWithCurrentYear = $employee->birthdate->copy()->year($now->year);

            if (BirthdayHelper::isBirthdayInRange($birthdateWithCurrentYear, $minDate, $maxDate)) {
                $birthdaysCollection->push([
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => ImageHelper::getAvatar($employee, 35),
                    'birthdate' => DateHelper::formatMonthAndDay($birthdateWithCurrentYear),
                    'sort_key' => Carbon::createFromDate($now->year, $birthdateWithCurrentYear->month, $birthdateWithCurrentYear->day)->format('Y-m-d'),
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $employee->id,
                    ]),
                ]);
            }
        }

        // sort the entries by soonest birthdates
        // we need to use values()->all() so it resets the keys properly.
        $birthdaysCollection = $birthdaysCollection->sortBy('sort_key')->values()->all();

        return $birthdaysCollection;
    }

    /**
     * Array containing all the information about the new employees this week.
     *
     * @param Company $company
     * @return Collection
     */
    public static function newHiresThisWeek(Company $company): Collection
    {
        $now = Carbon::now();
        $employees = $company->employees()
            ->notLocked()
            ->whereNotNull('hired_at')
            ->whereDate('hired_at', '>=', $now->copy()->startOfWeek(Carbon::MONDAY))
            ->whereDate('hired_at', '<=', $now->copy()->endOfWeek(Carbon::SUNDAY))
            ->with('position')
            ->orderBy('hired_at', 'asc')
            ->get();

        $newHiresCollection = collect([]);
        foreach ($employees as $employee) {
            $date = $employee->hired_at;
            $position = $employee->position;

            if ($position) {
                $dateString = $date->isPast() ?
                    trans('company.new_hires_date_with_position_past', [
                        'date' => DateHelper::formatDayAndMonthInParenthesis($date),
                        'position' => $position->title,
                    ]) : trans('company.new_hires_date_with_position_future', [
                        'date' => DateHelper::formatDayAndMonthInParenthesis($date),
                        'position' => $position->title,
                    ]);
            } else {
                $dateString = $date->isPast() ?
                    trans('company.new_hires_date_past', [
                        'date' => DateHelper::formatDayAndMonthInParenthesis($date),
                    ]) : trans('company.new_hires_date_future', [
                        'date' => DateHelper::formatDayAndMonthInParenthesis($date),
                    ]);
            }

            $newHiresCollection->push([
                'id' => $employee->id,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee->id,
                ]),
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 35),
                'hired_at' => $dateString,
            ]);
        }

        return $newHiresCollection;
    }

    /**
     * Array containing all the information about the latest Recent ships
     * shipped by any team.
     *
     * @param Company $company
     * @return Collection
     */
    public static function latestShips(Company $company): Collection
    {
        $ships = DB::table('ships')
            ->join('teams', 'ships.team_id', '=', 'teams.id')
            ->join('companies', 'teams.company_id', '=', 'companies.id')
            ->select('ships.id', 'ships.title', 'ships.team_id')
            ->where('companies.id', $company->id)
            ->orderBy('ships.created_at', 'desc')
            ->take(3)
            ->get();

        $shipsCollection = collect([]);
        foreach ($ships as $ship) {
            $shipsCollection->push([
                'url' => route('ships.show', [
                    'company' => $company,
                    'team' => $ship->team_id,
                    'ship' => $ship->id,
                ]),
                'title' => $ship->title,
            ]);
        }

        return $shipsCollection;
    }

    /**
     * Array containing all the information about the latest created skills.
     *
     * @param Company $company
     * @return array
     */
    public static function latestSkills(Company $company): array
    {
        $skillsCount = $company->skills()->count();

        $skills = $company->skills()
            ->select('id', 'name')
            ->latest('id')
            ->take(5)
            ->get();

        $skillsCollection = collect([]);
        foreach ($skills as $skill) {
            $skillsCollection->push([
                'name' => $skill->name,
                'url' => route('company.skills.show', [
                    'company' => $company,
                    'skill' => $skill,
                ]),
            ]);
        }

        return [
            'count' => $skillsCount,
            'view_all_url' => route('company.skills.index', [
                'company' => $company->id,
            ]),
            'skills' => $skillsCollection,
        ];
    }

    /**
     * Array containing all the information about the latest company news.
     *
     * @param Company $company
     * @return array
     */
    public static function latestNews(Company $company): array
    {
        $totalCompanyNews = $company->news()->count();

        $news = $company->news()
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        $newsCollection = collect([]);
        foreach ($news as $new) {
            $newsCollection->push([
                'id' => $new->id,
                'title' => $new->title,
                'created_at' => DateHelper::formatDate($new->created_at),
                'extract' => StringHelper::parse(Str::words($new->content, 20, ' ...')),
                'author_name' => $new->author_name,
            ]);
        }

        return [
            'count' => $totalCompanyNews,
            'news' => $newsCollection,
            'view_all_url' => route('company.news.index', [
                'company' => $company,
            ]),
        ];
    }

    /**
     * Array containing all the information about the Guess Employee Game.
     *
     * @param Employee $employee
     * @param Company $company
     * @return ?array
     */
    public static function guessEmployeeGameInformation(Employee $employee, Company $company): ?array
    {
        try {
            $game = (new CreateGuessEmployeeGame)->execute([
                'company_id' => $employee->company_id,
                'author_id' => $employee->id,
                'employee_id' => $employee->id,
            ]);
        } catch (OutOfRangeException $e) {
            return null;
        }

        $employeeToFind = $game->employeeToFind;
        $firstOtherEmployeeToFind = $game->firstOtherEmployeeToFind;
        $secondOtherEmployeeToFind = $game->secondOtherEmployeeToFind;

        $choices = collect();
        $choices->push([
            'id' => $employeeToFind->id,
            'name' => $employeeToFind->name,
            'position' => (! $employeeToFind->position) ? null : $employeeToFind->position->title,
            'right_answer' => true,
            'url' => route('employees.show', [
                'company' => $company,
                'employee' => $employeeToFind,
            ]),
        ]);
        $choices->push([
            'id' => $firstOtherEmployeeToFind->id,
            'name' => $firstOtherEmployeeToFind->name,
            'position' => (! $firstOtherEmployeeToFind->position) ? null : $firstOtherEmployeeToFind->position->title,
            'right_answer' => false,
        ]);
        $choices->push([
            'id' => $secondOtherEmployeeToFind->id,
            'name' => $secondOtherEmployeeToFind->name,
            'position' => (! $secondOtherEmployeeToFind->position) ? null : $secondOtherEmployeeToFind->position->title,
            'right_answer' => false,
        ]);

        $choices = $choices->shuffle();

        return [
            'id' => $game->id,
            'avatar_to_find' => ImageHelper::getAvatar($employeeToFind, 80),
            'choices' => $choices,
        ];
    }

    /**
     * Information about the employees in the company.
     *
     * @param Company $company
     * @return array
     */
    public static function employees(Company $company): array
    {
        // number of employees in total
        $totalNumberOfEmployees = $company->employees()->notLocked()->count();

        // 10 random employees
        $tenRandomEmployeesCollection = collect([]);
        $allEmployees = $company->employees()
            ->notLocked()
            ->with('picture')
            ->inRandomOrder()
            ->take(10)
            ->get();

        foreach ($allEmployees as $employee) {
            $tenRandomEmployeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 32),
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ]);
        }

        // ten random employees

        // number of employees hired in the current year
        $employeesHiredInTheCurrentYear = $company->employees()
            ->notLocked()
            ->whereYear('hired_at', (string) Carbon::now()->year)
            ->count();

        return [
            'employees_hired_in_the_current_year' => $employeesHiredInTheCurrentYear,
            'ten_random_employees' => $tenRandomEmployeesCollection,
            'number_of_employees_left' => $totalNumberOfEmployees - $tenRandomEmployeesCollection->count(),
            'view_all_url' => route('employees.index', [
                'company' => $company,
            ]),
        ];
    }

    public static function teams(Company $company): array
    {
        $randomTeams = $company
            ->teams()
            ->with('employees')
            ->inRandomOrder()
            ->take(3)
            ->get();

        $teamsCollection = collect();
        foreach ($randomTeams as $team) {
            $employeesCollection = collect([]);

            $employees = $team->employees()->with('picture')
                ->inRandomOrder()
                ->take(3)
                ->get();

            $numberOfEmployeesInTeam = $team->employees()->count();

            foreach ($employees as $employee) {
                $employeesCollection->push([
                    'id' => $employee->id,
                    'avatar' => ImageHelper::getAvatar($employee, 32),
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $employee,
                    ]),
                ]);
            }

            $remainingEmployees = $numberOfEmployeesInTeam - 3;

            $teamsCollection->push([
                'id' => $team->id,
                'name' => $team->name,
                'employees' => $employeesCollection,
                'total_remaining_employees' => $remainingEmployees < 0 ? 0 : $remainingEmployees,
                'url' => route('team.show', [
                    'company' => $company,
                    'team' => $team,
                ]),
            ]);
        }

        return [
            'random_teams' => $teamsCollection,
            'view_all_url' => route('teams.index', [
                'company' => $company,
            ]),
        ];
    }

    public static function upcomingHiredDateAnniversaries(Company $company)
    {
        $employees = $company->employees()
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
