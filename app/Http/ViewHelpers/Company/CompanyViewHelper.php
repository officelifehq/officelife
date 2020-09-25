<?php

namespace App\Http\ViewHelpers\Company;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Models\Company\Answer;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Question;
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
        // raw SQL queries to speed up performance, as this page will contain
        // a lot of data
        // a raw SQL query doesn't hydrate models, which will reduce the amount
        // of RAM needed to create the page
        $questionsCount = DB::table('questions')
            ->select('id')
            ->where('company_id', $company->id)
            ->count();

        // get the 3 latest questions asked to every employee of the company
        $latestQuestions = Question::addSelect(['count' => Answer::select('id')
                ->whereColumn('question_id', 'questions.id')
                ->groupBy('question_id')
                ->count(),
            ])
            ->where('company_id', $company->id)
            ->get();

        dd($latestQuestions);

        $latestQuestions = DB::select(
            'select questions.id as id, questions.title as title, count(answers.id) as count from questions, answers where company_id = ? and questions.id = answers.question_id group by questions.id;',
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
        // raw SQL queries to speed up performance, as this page will contain
        // a lot of data
        // in this case, and it’s a bit ugly, we need to format the query
        // depending if it’s mysql or sqlite, as they don’t have the same way
        // of formatting a date in the database
        if (config('default') === 'sqlite') {
            $employees = DB::select(
                "select id, concat(first_name, ' ', last_name) as name, avatar, birthdate from employees where company_id = :companyId and `locked` = false and (strftime('%m-%d', birthdate) > :lowerDate and strftime('%m-%d', birthdate') < :upperDate)",
                [
                    'companyId' => $company->id,
                    'lowerDate' => Carbon::now()->startOfWeek()->format('m-d'),
                    'upperDate' => Carbon::now()->endOfWeek()->format('m-d'),
                ]
            );
        } else {
            $employees = DB::select(
                "select id, concat(first_name, ' ', last_name) as name, avatar, birthdate from employees where company_id = :companyId and `locked` = false and (DATE_FORMAT(birthdate,'%m-%d') > :lowerDate and DATE_FORMAT(birthdate,'%m-%d') < :upperDate)",
                [
                    'companyId' => $company->id,
                    'lowerDate' => Carbon::now()->startOfWeek()->format('m-d'),
                    'upperDate' => Carbon::now()->endOfWeek()->format('m-d'),
                ]
            );
        }

        $birthdaysCollection = collect([]);
        foreach ($employees as $employee) {
            $date = Carbon::createFromFormat('Y-m-d h:i:s', $employee->birthdate);

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
}
