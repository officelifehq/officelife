<?php

namespace App\Http\Controllers\Company\Employee\Presentation;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Employee\EmployeeHierarchyViewHelper;

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
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $employee = Employee::where('company_id', $loggedCompany->id)->findOrFail($employeeId);
        $search = $request->input('searchTerm');

        $employees = EmployeeHierarchyViewHelper::search($loggedCompany, $employee, $search);

        return response()->json([
            'data' => $employees,
        ], 200);
    }
}
