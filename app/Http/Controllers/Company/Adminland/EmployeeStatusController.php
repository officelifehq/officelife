<?php

namespace App\Http\Controllers\Company\Adminland;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Services\Company\Adminland\EmployeeStatus\CreateEmployeeStatus;
use App\Services\Company\Adminland\EmployeeStatus\UpdateEmployeeStatus;
use App\Services\Company\Adminland\EmployeeStatus\DestroyEmployeeStatus;
use App\Http\Resources\Company\EmployeeStatus\EmployeeStatus as EmployeeStatusResource;

class EmployeeStatusController extends Controller
{
    /**
     * Show the list of employee statuses.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = Cache::get('currentCompany');
        $employeeStatuses = EmployeeStatusResource::collection(
            $company->employeeStatuses()->orderBy('name', 'asc')->get()
        );

        return View::component('ShowAccountEmployeeStatuses', [
            'company' => $company,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
            'notifications' => auth()->user()->getLatestNotifications($company),
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
        $request = [
            'company_id' => $companyId,
            'author_id' => auth()->user()->id,
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
        $request = [
            'company_id' => $companyId,
            'author_id' => auth()->user()->id,
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
        $request = [
            'company_id' => $companyId,
            'employee_status_id' => $employeeStatusId,
            'author_id' => auth()->user()->id,
        ];

        (new DestroyEmployeeStatus)->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
