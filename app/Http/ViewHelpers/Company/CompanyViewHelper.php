<?php

namespace App\Http\ViewHelpers\Company;

use Carbon\Carbon;
use OutOfRangeException;
use App\Helpers\DateHelper;
use Illuminate\Support\Str;
use App\Helpers\ImageHelper;
use App\Models\User\Pronoun;
use App\Helpers\StringHelper;
use App\Helpers\BirthdayHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
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
            'logo' => $company->logo ? ImageHelper::getImage($company->logo, 200, 200) : null,
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
            ->groupBy('questions.id')
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
                'url' => route('company.questions.show', [
                    'company' => $company,
                    'question' => $question->id,
                ]),
            ];
        });

        return [
            'total_number_of_questions' => $questionsCount,
            'all_questions_url' => route('company.questions.index', [
                'company' => $company->id,
            ]),
            'questions' => $questionCollection,
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
            ->where('locked', false)
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
            ->where('locked', false)
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

            $newHiresCollection->push([
                'id' => $employee->id,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee->id,
                ]),
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 35),
                'hired_at' => DateHelper::formatDayAndMonthInParenthesis($date),
                'position' => (! $position) ? null : $position->title,
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
            ->latest()
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
        $companyNewsCount = $company->news()->count();

        $news = $company->news()
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        $newsCollection = collect([]);
        foreach ($news as $new) {
            $newsCollection->push([
                'title' => $new->title,
                'extract' => StringHelper::parse(Str::words($new->content, 20, ' ...')),
                'author_name' => $new->author_name,
            ]);
        }

        return [
            'count' => $companyNewsCount,
            'news' => $newsCollection,
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

    /**
     * Information about employee's genders in the company.
     *
     * @param Company $company
     * @return array
     */
    public static function demography(Company $company): array
    {
        $stat = DB::table('employees')
            ->where('locked', false)
            ->join('pronouns', 'employees.pronoun_id', '=', 'pronouns.id')
            ->selectRaw("count(case when pronouns.label = '".Pronoun::HE."' then 1 end) as he")
            ->selectRaw("count(case when pronouns.label = '".Pronoun::SHE."' then 1 end) as she")
            ->selectRaw("count(case when pronouns.label = '".Pronoun::THEY."' then 1 end) as they")
            ->selectRaw("count(case when pronouns.label = '".Pronoun::PER."' then 1 end) as per")
            ->selectRaw("count(case when pronouns.label = '".Pronoun::VE."' then 1 end) as ve")
            ->selectRaw("count(case when pronouns.label = '".Pronoun::XE."' then 1 end) as xe")
            ->selectRaw("count(case when pronouns.label = '".Pronoun::ZE."' then 1 end) as ze")
            ->first();

        return [
            'he' => $stat->he,
            'she' => $stat->she,
            'they' => $stat->they,
            'per' => $stat->per,
            've' => $stat->ve,
            'xe' => $stat->xe,
            'ze' => $stat->ze,
        ];
    }
}
