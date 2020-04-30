<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\Company\Employee\EmployeeListWithoutTeams as EmployeeResource;

class EmployeeSearchController extends Controller
{
    /**
     * Return a list of available employees that can be associated as the
     * manager OR direct report of the given employee.
     * The rules are:
     * - the list should not contain the current employee
     * - the list should not contain the current managers of this employee
     * - the list should not contain the current direct reports of this employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return AnonymousResourceCollection
     */
    public function hierarchy(Request $request, int $companyId, int $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        $search = $request->get('searchTerm');
        $potentialEmployees = Employee::search(
            $search,
            $companyId,
            10,
            'created_at desc'
        );

        // remove the existing managers of this employee from the list
        $existingManagersForTheEmployee = $employee->getListOfManagers();
        $potentialEmployees = $potentialEmployees->diff($existingManagersForTheEmployee);

        // remove the existing direct reports of this employee from the list
        $existingDirectReportsForTheEmployee = $employee->getListOfDirectReports();
        $potentialEmployees = $potentialEmployees->diff($existingDirectReportsForTheEmployee);

        // remove the current employee from the list
        $potentialEmployees = $potentialEmployees->whereNotIn('id', $employee->id);

        return EmployeeResource::collection($potentialEmployees);
    }
}
