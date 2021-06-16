<?php

namespace App\Http\Controllers\Company\Dashboard\Me;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\MoneyHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Expense\CreateExpense;

class DashboardMeExpenseController extends Controller
{
    /**
     * Log an expense.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = [
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'expense_category_id' => $request->input('category'),
            'title' => $request->input('title'),
            'amount' => MoneyHelper::parseInput($request->input('amount'), $request->input('currency')),
            'currency' => $request->input('currency'),
            'description' => $request->input('description'),
            'expensed_at' => Carbon::now()->format('Y-m-d'),
        ];

        $expense = (new CreateExpense)->execute($data);

        return response()->json([
            'data' => [
                'id' => $expense->id,
                'title' => $expense->title,
                'amount' => MoneyHelper::format($expense->amount, $expense->currency),
                'status' => $expense->status,
                'category' => ($expense->category) ? $expense->category->name : null,
                'expensed_at' => DateHelper::formatDate($expense->expensed_at, $employee->timezone),
                'url' => route('employee.administration.expenses.show', [
                    'company' => $employee->company,
                    'employee' => $employee,
                    'expense' => $expense,
                ]),
            ],
        ], 200);
    }
}
