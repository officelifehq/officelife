<?php

namespace App\Http\Controllers\Company\Dashboard;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Http\Collections\AnswerCollection;
use App\Services\Company\Employee\Answer\CreateAnswer;
use App\Services\Company\Employee\Answer\UpdateAnswer;
use App\Services\Company\Adminland\Answer\DestroyAnswer;

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

    /**
     * Update the company news.
     *
     * @param Request $request
     * @param int     $companyId
     * @param int     $answerId
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $companyId, int $answerId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $loggedEmployee->id,
            'answer_id' => $answerId,
            'body' => $request->input('body'),
        ];

        $answer = (new UpdateAnswer)->execute($request);

        return response()->json([
            'data' => $answer->toObject(),
        ], 200);
    }

    /**
     * Delete the question.
     *
     * @param Request $request
     * @param int     $companyId
     * @param int     $companyNewsId
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $companyId, int $answerId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $loggedEmployee->id,
            'answer_id' => $answerId,
        ];

        (new DestroyAnswer)->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
