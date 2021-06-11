<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Adminland\AdminEmployeeStatusViewHelper;
use App\Services\Company\Adminland\EmployeeStatus\CreateEmployeeStatus;
use App\Services\Company\Adminland\EmployeeStatus\UpdateEmployeeStatus;
use App\Services\Company\Adminland\EmployeeStatus\DestroyEmployeeStatus;

class AdminEmployeeStatusController extends Controller
{
    /**
     * Show the list of employee statuses.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        $statusCollection = AdminEmployeeStatusViewHelper::index($company);

        return Inertia::render('Adminland/EmployeeStatus/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'statuses' => $statusCollection,
        ]);
    }

    /**
     * Create the employee status.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'name' => $request->input('name'),
            'type' => $request->input('type'),
        ];

        $employeeStatus = (new CreateEmployeeStatus)->execute($data);

        return response()->json([
            'data' => AdminEmployeeStatusViewHelper::show($employeeStatus),
        ], 201);
    }

    /**
     * Update the employee status.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeStatusId
     * @return JsonResponse
     */
    public function update(Request $request, $companyId, int $employeeStatusId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_status_id' => $employeeStatusId,
            'name' => $request->input('name'),
            'type' => $request->input('type'),
        ];

        $employeeStatus = (new UpdateEmployeeStatus)->execute($data);

        return response()->json([
            'data' => AdminEmployeeStatusViewHelper::show($employeeStatus),
        ], 200);
    }

    /**
     * Delete the employee status.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeStatusId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $employeeStatusId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'employee_status_id' => $employeeStatusId,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyEmployeeStatus)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
