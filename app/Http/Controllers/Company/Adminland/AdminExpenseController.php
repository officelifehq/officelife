<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Adminland\AdminExpenseViewHelper;
use App\Services\Company\Adminland\Expense\AllowEmployeeToManageExpenses;
use App\Services\Company\Adminland\ExpenseCategory\CreateExpenseCategory;
use App\Services\Company\Adminland\ExpenseCategory\UpdateExpenseCategory;
use App\Services\Company\Adminland\ExpenseCategory\DestroyExpenseCategory;
use App\Services\Company\Adminland\Expense\DisallowEmployeeToManageExpenses;

class AdminExpenseController extends Controller
{
    /**
     * Show the list of expense categories.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        $categoriesCollection = AdminExpenseViewHelper::categories($company);
        $employeesCollection = AdminExpenseViewHelper::employees($company);

        return Inertia::render('Adminland/Expense/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'categories' => $categoriesCollection,
            'employees' => $employeesCollection,
        ]);
    }

    /**
     * Create a new expense category.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'name' => $request->input('name'),
        ];

        $category = (new CreateExpenseCategory)->execute($data);

        return response()->json([
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
        ], 201);
    }

    /**
     * Update the expense category.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $expenseCategoryId
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId, int $expenseCategoryId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'expense_category_id' => $expenseCategoryId,
            'name' => $request->input('name'),
        ];

        $category = (new UpdateExpenseCategory)->execute($data);

        return response()->json([
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
        ], 200);
    }

    /**
     * Delete the expense category.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $expenseCategoryId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $expenseCategoryId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'expense_category_id' => $expenseCategoryId,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyExpenseCategory)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Search an employee to add him as someone with the right to manage
     * expenses in the company.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function search(Request $request, int $companyId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $employees = AdminExpenseViewHelper::search($loggedCompany, $request->input('searchTerm'));

        return response()->json([
            'data' => $employees,
        ], 200);
    }

    /**
     * Assign an employee to let him manage expenses company wide.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function addEmployee(Request $request, int $companyId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $request->input('selectedEmployee'),
        ];

        $employee = (new AllowEmployeeToManageExpenses)->execute($data);

        return response()->json([
            'data' => [
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 23),
                'url' => route('employees.show', [
                    'company' => $loggedCompany,
                    'employee' => $employee,
                ]),
            ],
        ], 201);
    }

    /**
     * Unassing an employee to let him manage expenses company wide.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function removeEmployee(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $request->input('selectedEmployee'),
        ];

        $employee = (new DisallowEmployeeToManageExpenses)->execute($data);

        return response()->json([
            'data' => [
                'id' => $employee->id,
            ],
        ], 200);
    }
}
