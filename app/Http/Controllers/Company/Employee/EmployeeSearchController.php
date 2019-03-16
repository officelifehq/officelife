<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class EmployeeSearchController extends Controller
{
    /**
     * Return a list of available employees that can be associated as the
     * manager of the given employee.
     * The rules are:
     * - the list should not contain the current employee
     * - the list should not contain the current managers of this employee
     * - the list should not contain the current direct reports of this employee
     *
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
     */
    public function managers(Request $request, int $companyId, int $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        $search = $request->get('searchTerm');
        $potentialManagers = Employee::search(
            $search,
            Cache::get('currentCompany')->id,
            10,
            'created_at desc'
        );

        // remove the existing managers of this employee from the list
        $existingManagersForTheEmployee = $employee->getListOfManagers();
        $potentialManagers = $potentialManagers->diff($existingManagersForTheEmployee);

        // remove the existing direct reports of this employee from the list
        $existingDirectReportsForTheEmployee = $employee->getListOfDirectReports();
        $potentialManagers = $potentialManagers->diff($existingDirectReportsForTheEmployee);

        // remove the current employee from the list
        $potentialManagers = $potentialManagers->whereNotIn('id', $employee);

        return EmployeeResource::collection($potentialManagers);
    }
}
