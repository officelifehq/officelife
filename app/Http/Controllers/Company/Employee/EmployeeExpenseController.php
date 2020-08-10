<?php

namespace App\Http\Controllers\Company\Employee;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Dashboard\DashboardExpenseViewHelper;

class EmployeeExpenseController extends Controller
{
    /**
     * Display a single expense.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @param int $expenseId
     *
     * @return Response
     */
    public function show(Request $request, int $companyId, int $employeeId, int $expenseId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        try {
            $expense = Expense::where('company_id', $loggedCompany->id)
                ->findOrFail($expenseId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        if ($expense->employee) {
            if ($expense->employee_id != $employeeId) {
                return redirect('home');
            }
        }

        // the expense can only be seen by the employee who submitted it
        // or by someone with the accountant role
        if (! $loggedEmployee->can_manage_expenses && $loggedEmployee->id != $employeeId) {
            return redirect('home');
        }

        return Inertia::render('Employee/Expenses/Show', [
            'employee' => [
                'id' => $employeeId,
            ],
            'expense' => DashboardExpenseViewHelper::expense($expense),
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }
}
