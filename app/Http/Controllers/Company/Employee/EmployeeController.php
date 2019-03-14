<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;
use App\Services\Company\Employee\AssignManager;

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

        $managersCollection = collect([]);
        foreach ($employee->reportsTo()->get() as $directReport) {
            $managersCollection->push($directReport->manager);
        }

        $directReportCollection = collect([]);
        foreach ($employee->managerOf()->get() as $directReport) {
            $directReportCollection->push($directReport->diredirectReport);
        }

        return View::component('ShowCompanyEmployee', [
            'company' => $company,
            'user' => auth()->user()->isPartOfCompany($company),
            'employee' => new EmployeeResource($employee),
            'managers' => EmployeeResource::collection($managersCollection),
            'directReports' => EmployeeResource::collection($directReportCollection),
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
}
