<?php

namespace App\Http\Controllers\Company\Dashboard;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\DirectReport;
use App\Jobs\UpdateDashboardPreference;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Dashboard\DashboardManagerViewHelper;
use App\Services\Company\Employee\Expense\AcceptExpenseAsManager;
use App\Services\Company\Employee\Expense\RejectExpenseAsManager;

class DashboardManagerController extends Controller
{
    /**
     * All information that the manager needs to validate.
     *
     * @return mixed
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // is this person a manager?
        $directReports = DirectReport::where('company_id', $company->id)
            ->where('manager_id', $employee->id)
            ->with('directReport')
            ->with('directReport.expenses')
            ->get();

        if ($directReports->count() == 0) {
            return redirect('home');
        }

        UpdateDashboardPreference::dispatch([
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'view' => 'manager',
        ])->onQueue('low');

        $employeeInformation = [
            'id' => $employee->id,
            'dashboard_view' => 'manager',
            'is_manager' => true,
            'can_manage_expenses' => $employee->can_manage_expenses,
        ];

        $pendingExpenses = DashboardManagerViewHelper::pendingExpenses($directReports);

        return Inertia::render('Dashboard/Manager/Index', [
            'employee' => $employeeInformation,
            'notifications' => NotificationHelper::getNotifications($employee),
            'pendingExpenses' => $pendingExpenses,
            'defaultCompanyCurrency' => $company->currency,
        ]);
    }

    /**
     * Check that the current employee has access to this method.
     * @param Company $company
     * @param int $expenseId
     * @param Employee $employee
     * @return mixed
     */
    private function canAccess(Company $company, int $expenseId, Employee $employee)
    {
        // can this expense been seen by someone in this company?
        try {
            $expense = Expense::where('company_id', $company->id)
                ->findOrFail($expenseId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        if ($expense->status !== Expense::AWAITING_MANAGER_APPROVAL) {
            return redirect('home');
        }

        // is the user a manager?
        $directReports = DirectReport::where('company_id', $company->id)
            ->where('manager_id', $employee->id)
            ->with('directReport')
            ->with('directReport.expenses')
            ->get();

        if ($directReports->count() == 0) {
            return redirect('home');
        }

        // can the manager see this expense?
        if (! $employee->isManagerOf($expense->employee->id)) {
            return redirect('home');
        }

        return $expense;
    }

    /**
     * Display the expense.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $expenseId
     * @return Response
     */
    public function showExpense(Request $request, int $companyId, int $expenseId): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $expense = $this->canAccess($company, $expenseId, $employee);

        $expense = DashboardManagerViewHelper::expense($expense);

        return Inertia::render('Dashboard/Manager/ApproveExpense', [
            'notifications' => NotificationHelper::getNotifications($employee),
            'expense' => $expense,
            'defaultCompanyCurrency' => $company->currency,
        ]);
    }

    /**
     * Accept the expense.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $expenseId
     * @return JsonResponse
     */
    public function accept(Request $request, int $companyId, int $expenseId): JsonResponse
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
     * @param Request $request
     * @param int $companyId
     * @param int $expenseId
     * @return JsonResponse
     */
    public function reject(Request $request, int $companyId, int $expenseId): JsonResponse
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
