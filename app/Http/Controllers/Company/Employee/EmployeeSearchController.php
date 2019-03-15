<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

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

        $company = Cache::get('currentCompany');
        $potentialManagers = $company->employees()->get();

        // remove the existing managers of this employee from the list
        $existingManagersForTheEmployee = $employee->getListOfManagers();
        $potentialManagers = $potentialManagers->whereNotIn('id', $existingManagersForTheEmployee);

        // remove the existing direct reports of this employee from the list
        $existingDirectReportsForTheEmployee = $employee->getListOfDirectReports();
        $potentialManagers = $potentialManagers->whereNotIn('id', $existingDirectReportsForTheEmployee);

        // remove the current employee from the list
        $potentialManagers = $potentialManagers->whereNotIn('id', $employee);

        return $potentialManagers;
    }
}
