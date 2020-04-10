<?php

namespace App\Http\Controllers\Company\Dashboard;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Http\Collections\AnswerCollection;
use App\Services\Company\Employee\Answer\CreateAnswer;

class DashboardQuestionController extends Controller
{
    /**
     * Answer the question.
     *
     * @var Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $request = [
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'question_id' => $request->input('id'),
            'body' => $request->input('body'),
        ];

        $answer = (new CreateAnswer)->execute($request);

        $allEmployeeAnswers = $answer->question->answers()->with('employee')->get();

        return response()->json([
            'data' => AnswerCollection::prepare($allEmployeeAnswers),
        ], 200);
    }
}
