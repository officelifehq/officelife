<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Adminland\EmployeeStatus\CreateEmployeeStatus;
use App\Services\Company\Adminland\EmployeeStatus\UpdateEmployeeStatus;
use App\Services\Company\Adminland\EmployeeStatus\DestroyEmployeeStatus;
use App\Http\Resources\Company\EmployeeStatus\EmployeeStatus as EmployeeStatusResource;

class AdminEmployeeStatusController extends Controller
{
    /**
     * Show the list of employee statuses.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $employeeStatuses = EmployeeStatusResource::collection(
            $company->employeeStatuses()->orderBy('name', 'asc')->get()
        );

        return Inertia::render('Adminland/EmployeeStatus/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'statuses' => $employeeStatuses,
        ]);
    }

    /**
     * Create the employee status.
     *
     * @param Request $request
     * @param int $companyId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $companyId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'name' => $request->get('name'),
        ];

        $employeeStatus = (new CreateEmployeeStatus)->execute($request);

        return response()->json([
            'data' => $employeeStatus,
        ]);
    }

    /**
     * Update the employee status.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeStatusId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $companyId, $employeeStatusId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_status_id' => $employeeStatusId,
            'name' => $request->get('name'),
        ];

        $employeeStatus = (new UpdateEmployeeStatus)->execute($request);

        return new EmployeeStatusResource($employeeStatus);
    }

    /**
     * Delete the employee status.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeStatusId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $companyId, $employeeStatusId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'employee_status_id' => $employeeStatusId,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyEmployeeStatus)->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
