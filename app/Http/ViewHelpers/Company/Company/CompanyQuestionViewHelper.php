<?php

namespace App\Http\ViewHelpers\Company\Company;

use App\Helpers\DateHelper;
use App\Models\Company\Team;
use App\Models\Company\Answer;
use App\Helpers\QuestionHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Question;
use Illuminate\Support\Collection;

class CompanyQuestionViewHelper
{
    /**
     * Array containing all the information about the questions.
     *
     * @param Company $company
     *
     * @return Collection|null
     */
    public static function questions(Company $company): ?Collection
    {
        // get all questions
        $questions = $company->questions()->get();

        // if no active question
        if ($questions->count() == 0) {
            return null;
        }

        // building a collection of questions
        $questionCollection = collect([]);
        foreach ($questions as $question) {
            $numberOfAnswers = Answer::where('question_id', $question->id)->count();

            if ($numberOfAnswers == 0) {
                continue;
            }

            $questionCollection->push([
                'id' => $question->id,
                'title' => $question->title,
                'number_of_answers' => $numberOfAnswers,
                'url' => route('company.questions.show', [
                    'company' => $company,
                    'question' => $question,
                ]),
            ]);
        }

        return $questionCollection;
    }

    /**
     * Detail of a question, along with all the answers and the answer of the
     * employee passed in parameter.
     *
     * @param Question $question
     * @param mixed $answers
     * @param Employee $employee
     *
     * @return array|null
     */
    public static function question(Question $question, $answers, Employee $employee): ?array
    {
        $detailOfAnswer = QuestionHelper::getAnswer($question, $employee);

        // building the sentence `This question was asked from Jan 20, 2020 to Mar 21, 2020`
        $date = CompanyQuestionViewHelper::getInformationAboutActivationDate($question);

        // preparing the array of answers
        $answerCollection = collect([]);
        foreach ($answers as $answer) {
            $answerCollection->push([
                'id' => $answer->answer_id,
                'body' => $answer->body,
                'employee' => [
                    'name' => $answer->employee->name,
                    'avatar' => $answer->employee->avatar,
                ],
            ]);
        }

        $array = [
            'id' => $question->id,
            'title' => $question->title,
            'number_of_answers' => $answers->count(),
            'answers' => $answerCollection,
            'employee_has_answered' => $detailOfAnswer ? true : false,
            'answer_by_employee' => $detailOfAnswer,
            'date' => $date,
            'url' => route('company.questions.show', [
                'company' => $employee->company,
                'question' => $question,
            ]),
        ];

        return $array;
    }

    /**
     * Detail of a question, along with all the answers only written by
     * employees in a team.
     *
     * @param Question $question
     * @param mixed $answers
     * @param Employee $employee
     *
     * @return array|null
     */
    public static function teams(Question $question, $answers, Employee $employee): ?array
    {
        $answerByEmployee = QuestionHelper::getAnswer($question, $employee);

        $date = CompanyQuestionViewHelper::getInformationAboutActivationDate($question);

        // preparing the array of answers
        $answerCollection = collect([]);
        foreach ($answers as $answer) {
            $answerCollection->push([
                'id' => $answer->answer_id,
                'body' => $answer->body,
                'employee' => [
                    'name' => $answer->name,
                    'avatar' => $answer->avatar,
                ],
            ]);
        }

        $array = [
            'id' => $question->id,
            'title' => $question->title,
            'number_of_answers' => $answers->count(),
            'answers' => $answerCollection,
            'employee_has_answered' => $answerByEmployee ? true : false,
            'answer_by_employee' => $answerByEmployee,
            'date' => $date,
            'url' => route('company.questions.show', [
                'company' => $employee->company,
                'question' => $question,
            ]),
        ];

        return $array;
    }

    /**
     * @param Question $question
     * @return string|null
     */
    private static function getInformationAboutActivationDate(Question $question): ?string
    {
        // building the sentence `This question was asked from Jan 20, 2020 to Mar 21, 2020`
        $date = null;
        if ($question->activated_at && $question->deactivated_at) {
            $start = DateHelper::formatFullDate($question->activated_at);
            $end = DateHelper::formatFullDate($question->deactivated_at);

            $date = trans('company.question_date_range', [
                'start_date' => $start,
                'end_date' => $end,
            ]);
        }

        if ($question->activated_at && ! $question->deactivated_at) {
            $start = DateHelper::formatFullDate($question->activated_at);

            $date = trans('company.question_date_range_no_deactivated', [
                'start_date' => $start,
            ]);
        }

        return $date;
    }
}
