<?php

namespace App\Http\ViewHelpers\Company;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CompanyViewHelper
{
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
        $latestQuestions = DB::select(
            'select questions.id as id, questions.title as title, count(answers.id) as count from questions, answers where company_id = ? and questions.id = answers.question_id group by questions.id limit 3;',
            [$company->id]
        );

        // building a collection of questions
        $questionCollection = collect([]);
        foreach ($latestQuestions as $question) {
            $numberOfAnswers = $question->count;

            if ($numberOfAnswers == 0) {
                continue;
            }

            $questionCollection->push([
                'id' => $question->id,
                'title' => $question->title,
                'number_of_answers' => $numberOfAnswers,
                'url' => route('company.questions.show', [
                    'company' => $company,
                    'question' => $question->id,
                ]),
            ]);
        }

        return [
            'total_number_of_questions' => $questionsCount,
            'latest_questions' => $questionCollection,
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
            ->select('id', 'first_name', 'last_name', 'avatar', 'birthdate')
            ->where('locked', false)
            ->whereMonth('birthdate', '>=', Carbon::now()->startOfWeek(Carbon::MONDAY)->format('m'))
            ->WhereMonth('birthdate', '<', Carbon::now()->endOfWeek(Carbon::SUNDAY)->format('m') + 1)
            ->whereDay('birthdate', '>=', Carbon::now()->startOfWeek(Carbon::MONDAY)->format('d'))
            ->WhereDay('birthdate', '<', Carbon::now()->endOfWeek(Carbon::SUNDAY)->format('d') + 1)
            ->get();

        $birthdaysCollection = collect([]);
        foreach ($employees as $employee) {
            $date = $employee->birthdate;

            $birthdaysCollection->push([
                'id' => $employee->id,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee->id,
                ]),
                'name' => $employee->name,
                'avatar' => $employee->avatar,
                'birthdate' => DateHelper::formatMonthAndDay($date),
                'sort_key' => Carbon::createFromDate(Carbon::now()->year, $date->month, $date->day)->format('Y-m-d'),
            ]);
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
        $employees = $company->employees()
            ->select('id', 'first_name', 'last_name', 'avatar', 'hired_at')
            ->where('locked', false)
            ->whereNotNull('hired_at')
            ->whereDate('hired_at', '>=', Carbon::now()->startOfWeek(Carbon::MONDAY))
            ->whereDate('hired_at', '<=', Carbon::now()->endOfWeek(Carbon::SUNDAY))
            ->orderBy('hired_at', 'asc')
            ->get();

        $newHiresCollection = collect([]);
        foreach ($employees as $employee) {
            $date = $employee->hired_at;

            $newHiresCollection->push([
                'id' => $employee->id,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee->id,
                ]),
                'name' => $employee->name,
                'avatar' => $employee->avatar,
                'hired_at' => DateHelper::formatMonthAndDay($date),
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
            'skills' => $skillsCollection,
        ];
    }
}
