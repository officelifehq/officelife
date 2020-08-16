<?php

namespace App\Http\Controllers\Company\Dashboard;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Expense;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Expense\AcceptExpenseAsManager;
use App\Services\Company\Employee\Expense\RejectExpenseAsManager;

class DashboardRateYourManagerController extends Controller
{
    /**
     * Store the answer of the Rate your manager survey.
     *
     * @return \Inertia\Response
     */
    public function store(Request $request, int $surveyId, string $answer)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $expense = $this->canAccess($company, $expenseId, $employee);

        $request = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'expense_id' => $expenseId,
        ];

        $expense = (new AcceptExpenseAsManager)->execute($request);

        return response()->json([
            'data' => $expense->id,
        ], 201);
    }

    /**
     * Reject the expense.
     *
     * @return \Inertia\Response
     */
    public function reject(Request $request, int $companyId, int $expenseId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $expense = $this->canAccess($company, $expenseId, $employee);

        $request = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'expense_id' => $expenseId,
            'reason' => $request->input('reason'),
        ];

        $expense = (new RejectExpenseAsManager)->execute($request);

        return response()->json([
            'data' => $expense->id,
        ], 201);
    }
}
