<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

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
     * @return JsonResponse
     */
    public function hierarchy(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $employee = Employee::findOrFail($employeeId);

        $search = $request->input('searchTerm');
        $potentialEmployees = Employee::search(
            $search,
            $companyId,
            10,
            'created_at desc',
            'and locked = false',
        );

        // remove the existing managers of this employee from the list
        $existingManagersForTheEmployee = $employee->getListOfManagers();
        $potentialEmployees = $potentialEmployees->diff($existingManagersForTheEmployee);

        // remove the existing direct reports of this employee from the list
        $existingDirectReportsForTheEmployee = $employee->getListOfDirectReports();
        $potentialEmployees = $potentialEmployees->diff($existingDirectReportsForTheEmployee);

        // remove the current employee from the list
        $potentialEmployees = $potentialEmployees->whereNotIn('id', $employee->id);

        $collection = collect([]);
        foreach ($potentialEmployees as $employee) {
            $collection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'url' => route('employees.show', [
                    'company' => $employee->company,
                    'employee' => $employee,
                ]),
            ]);
        }

        return response()->json([
            'data' => $collection,
        ], 200);
    }
}
