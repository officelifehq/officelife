<?php

namespace App\Http\Controllers\Company\Adminland;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Adminland\Employee\ChangePermission;

class PermissionController extends Controller
{
    /**
     * Change permission.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, int $companyId, int $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'permission_level' => $request->input('permission_level'),
        ];

        (new ChangePermission)->execute($data);

        return redirect(tenant('/account/employees'));
    }
}
