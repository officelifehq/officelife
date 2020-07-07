<?php

namespace App\Http\Controllers\Company\Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Expense\CreateExpense;

class DashboardExpenseController extends Controller
{
    /**
     * Log an expense.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // expense category
        $category = $request->input('category') ? $request->input('category')['id'] : null;

        $request = [
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'expense_category_id' => $category,
            'title' => $request->input('title'),
            'amount' => $request->input('amount') * 100,
            'currency' => $request->input('currency')['code'],
            'description' => $request->input('description'),
            'expensed_at' => Carbon::now()->format('Y-m-d'),
        ];

        $expense = (new CreateExpense)->execute($request);

        return response()->json([
            'data' => [
                'id' => $expense->id,
                'title' => $expense->title,
            ],
        ], 200);
    }
}
