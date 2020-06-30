<?php

namespace App\Http\ViewHelpers\Dashboard;

use App\Helpers\QuestionHelper;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;

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
                    'avatar' => $answer->employee->avatar,
                ],
            ]);
        }

        $response = [
            'id' => $question->id,
            'title' => $question->title,
            'number_of_answers' => $allAnswers->count(),
            'answers' => $answersCollection,
            'employee_has_answered' => $answerByEmployee ? true : false,
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
}
