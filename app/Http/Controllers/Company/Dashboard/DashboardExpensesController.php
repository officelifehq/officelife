<?php

namespace App\Http\Controllers\Company\Dashboard;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Expense;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Dashboard\DashboardExpenseViewHelper;

/**
 * This is the controller showing the Expenses tab for the Accountant role.
 */
class DashboardExpensesController extends Controller
{
    /**
     * Display all expenses in the company.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $awaitingAccountingExpenses = DashboardExpenseViewHelper::waitingForAccountingApproval($company);
        $awaitingManagerExpenses = DashboardExpenseViewHelper::waitingForManagerApproval($company);

        $employeeInformation = [
            'id' => $employee->id,
            'dashboard_view' => 'expenses',
            'can_manage_expenses' => $employee->can_manage_expenses,
        ];

        return Inertia::render('Dashboard/Expenses/Index', [
            'employee' => $employeeInformation,
            'awaitingAccountingExpenses' => $awaitingAccountingExpenses,
            'awaitingManagerExpenses' => $awaitingManagerExpenses,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Display all expenses in the company.
     *
     * @return \Inertia\Response
     */
    public function show(Request $request, int $companyId, int $expenseId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        try {
            $expense = Expense::where('company_id', $company->id)
                ->findOrFail($expenseId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Dashboard/Expenses/Show', [
            'expense' => DashboardExpenseViewHelper::expense($expense),
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }
}
