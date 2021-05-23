<?php

namespace App\Http\ViewHelpers\Company;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
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
        $questions = $company->questions()->with('answers')->orderBy('id', 'desc')->get();

        // if no active question
        if ($questions->count() == 0) {
            return null;
        }

        // building a collection of questions
        $questionCollection = collect([]);
        foreach ($questions as $question) {
            $numberOfAnswers = $question->answers->count();

            if ($numberOfAnswers == 0 && ! $question->active) {
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
        $date = self::getInformationAboutActivationDate($question);

        // preparing the array of answers
        $answerCollection = collect([]);
        foreach ($answers as $answer) {
            $answerCollection->push([
                'id' => $answer->answer_id,
                'body' => $answer->body,
                'employee' => [
                    'name' => $answer->employee->name,
                    'avatar' => ImageHelper::getAvatar($answer->employee, 22),
                ],
            ]);
        }

        $array = [
            'id' => $question->id,
            'title' => $question->title,
            'number_of_answers' => $answers->count(),
            'answers' => $answerCollection,
            'employee_has_answered' => (bool) $detailOfAnswer,
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
     * Array containing information about the teams.
     *
     * @param Collection $teams
     *
     * @return Collection
     */
    public static function teams(Collection $teams): Collection
    {
        $teamsCollection = collect([]);
        foreach ($teams as $team) {
            $teamsCollection->push([
                'id' => $team->id,
                'name' => $team->name,
            ]);
        }

        return $teamsCollection;
    }

    /**
     * @param Question $question
     *
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
