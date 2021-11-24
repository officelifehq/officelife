<?php

namespace App\Http\Controllers\Company\Dashboard\Manager;

use Inertia\Inertia;
use Inertia\Response;
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
use App\Http\ViewHelpers\Dashboard\DashboardViewHelper;
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
            ->with('directReport.status')
            ->with('directReport.position')
            ->with('directReport.expenses')
            ->with('directReport.expenses.employee')
            ->with('directReport.expenses.category')
            ->get();

        if ($directReports->count() == 0) {
            return redirect('home');
        }

        UpdateDashboardPreference::dispatch([
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'view' => 'manager',
        ])->onQueue('low');

        $pendingExpenses = DashboardManagerViewHelper::pendingExpenses($employee, $directReports);
        $oneOnOnes = DashboardManagerViewHelper::oneOnOnes($employee, $directReports);
        $contractRenewals = DashboardManagerViewHelper::contractRenewals($employee, $directReports);
        $timesheetsStats = DashboardManagerViewHelper::employeesWithTimesheetsToApprove($employee, $directReports);
        $disciplineCases = DashboardManagerViewHelper::activeDisciplineCases($company, $directReports);

        return Inertia::render('Dashboard/Manager/Index', [
            'employee' => DashboardViewHelper::information($employee, 'manager'),
            'notifications' => NotificationHelper::getNotifications($employee),
            'pendingExpenses' => $pendingExpenses,
            'oneOnOnes' => $oneOnOnes,
            'contractRenewals' => $contractRenewals,
            'timesheetsStats' => $timesheetsStats,
            'defaultCompanyCurrency' => $company->currency,
            'disciplinesCases' => $disciplineCases,
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

        $expense = DashboardManagerViewHelper::expense($expense, $employee);

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

        $this->canAccess($company, $expenseId, $employee);

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'expense_id' => $expenseId,
        ];

        $expense = (new AcceptExpenseAsManager)->execute($data);

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

        $this->canAccess($company, $expenseId, $employee);

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'expense_id' => $expenseId,
            'reason' => $request->input('reason'),
        ];

        $expense = (new RejectExpenseAsManager)->execute($data);

        return response()->json([
            'data' => $expense->id,
        ], 201);
    }
}
