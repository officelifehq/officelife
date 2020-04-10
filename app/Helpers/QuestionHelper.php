<?php

namespace App\Helpers;

use App\Models\Company\Employee;
use App\Models\Company\Question;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class QuestionHelper
{
    /**
     * Get the answer made by the employee for the given question.
     *
     * @param  Question   $question
     * @param  Employee   $employee
     * @return array|null
     */
    public static function getAnswer(Question $question, Employee $employee): ?array
    {
        try {
            $answer = $question->answers()->where('employee_id', $employee->id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }

        return $answer->toObject();
    }
}
