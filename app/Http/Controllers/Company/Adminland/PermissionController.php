<?php

namespace App\Http\Controllers\Company\Adminland;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use App\Services\Company\Adminland\Employee\ChangePermission;

class PermissionController extends Controller
{
    /**
     * Show the list of employees.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return Response
     */
    public function index(Request $request, int $companyId, int $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        return view('company.account.employee.permission')
            ->withEmployee($employee);
    }

    /**
     * Change permission.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return Response
     */
    public function store(Request $request, int $companyId, int $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'permission_level' => $request->get('permission_level'),
        ];

        (new ChangePermission)->execute($request);

        return redirect(tenant('/account/employees'));
    }
}
