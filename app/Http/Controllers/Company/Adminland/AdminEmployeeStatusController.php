<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Collections\EmployeeStatusCollection;
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
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $employeeStatuses = $company->employeeStatuses()->orderBy('name', 'asc')->get();

        $statusCollection = EmployeeStatusCollection::prepare($employeeStatuses);

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
     *
     * @return Response
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
            'data' => $employeeStatus->toObject(),
        ], 201);
    }

    /**
     * Update the employee status.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeStatusId
     *
     * @return Response
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

        return response()->json([
            'data' => $employeeStatus->toObject(),
        ], 200);
    }

    /**
     * Delete the employee status.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeStatusId
     *
     * @return Response
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
