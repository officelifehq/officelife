<?php

namespace App\Http\ViewHelpers\Company\Dashboard;

use App\Helpers\QuestionHelper;
use App\Models\Company\Employee;
use App\Http\Collections\AnswerCollection;

class DashboardMeViewHelper
{
    /**
     * Array containing all the information about the current active question.
     *
     * @param  Employee   $employee
     * @return array|null
     */
    public static function question(Employee $employee): ?array
    {
        // get active question
        $question = $employee->company->questions()->with('answers')->with('answers.employee')->active()->first();

        // if no active question
        if (!$question) {
            return null;
        }

        $allEmployeeAnswers = $question->answers;

        $detailOfAnswer = QuestionHelper::getAnswer($question, $employee);

        $array = [
            'id' => $question->id,
            'title' => $question->title,
            'number_of_answers' => $allEmployeeAnswers->count(),
            'answers' => AnswerCollection::prepare($allEmployeeAnswers->take(3)),
            'employee_has_answered' => $detailOfAnswer ? true : false,
            'answer_by_employee' => $detailOfAnswer,
            'url' => route('company.questions.show', [
                'company' => $employee->company,
                'question' => $question,
            ]),
        ];

        return $array;
    }
}
