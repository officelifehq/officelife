<?php

namespace App\Http\Controllers\Company\Employee;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Employee\EmployeeExpenseViewHelper;
use App\Http\ViewHelpers\Dashboard\DashboardExpenseViewHelper;

class EmployeeExpenseController extends Controller
{
    /**
     * Display the list of expenses of this employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return mixed
     */
    public function index(Request $request, int $companyId, int $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $employee = Employee::where('company_id', $companyId)
                ->where('id', $employeeId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $expenses = $employee->expenses()
            ->with('category')
            ->latest()
            ->get();

        return Inertia::render('Employee/Administration/Expenses/Index', [
            'employee' => [
                'id' => $employeeId,
                'name' => $employee->name,
            ],
            'expenses' => EmployeeExpenseViewHelper::list($employee, $expenses),
            'statistics' => EmployeeExpenseViewHelper::stats($employee, $expenses),
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }

    /**
     * Display a single expense.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @param int $expenseId
     * @return mixed
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

        return Inertia::render('Employee/Administration/Expenses/Show', [
            'employee' => [
                'id' => $employeeId,
            ],
            'expense' => DashboardExpenseViewHelper::expense($expense),
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }
}
