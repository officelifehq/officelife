<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Services\Adminland\Employee\AssignManager;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class EmployeeController extends Controller
{
    /**
     * Display the detail of an employee.
     *
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $companyId, int $employeeId)
    {
        $company = Cache::get('currentCompany');
        $employee = Employee::findOrFail($employeeId);

        $managers = $employee->getListOfManagers();
        $directReports = $employee->getListOfDirectReports();

        return View::component('ShowCompanyEmployee', [
            'company' => $company,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
            'employee' => new EmployeeResource($employee),
            'managers' => EmployeeResource::collection($managers),
            'directReports' => EmployeeResource::collection($directReports),
        ]);
    }

    /**
     * Assign a manager to the employee.
     *
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
     */
    public function assignManager(Request $request, int $companyId, int $employeeId)
    {
        $request = [
            'company_id' => $companyId,
            'author_id' => auth()->user()->id,
            'employee_id' => $employeeId,
            'manager_id' => $request->get('id'),
        ];

        $manager = (new AssignManager)->execute($request);

        return new EmployeeResource($manager);
    }

    /**
     * Assign a direct report to the employee.
     *
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
     */
    public function assignDirectReport(Request $request, int $companyId, int $employeeId)
    {
        $data = [
            'company_id' => $companyId,
            'author_id' => auth()->user()->id,
            'employee_id' => $request->get('id'),
            'manager_id' => $employeeId,
        ];

        (new AssignManager)->execute($data);

        $directReport = Employee::findOrFail($request->get('id'));

        return new EmployeeResource($directReport);
    }
}
