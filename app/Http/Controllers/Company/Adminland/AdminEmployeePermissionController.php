<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\Employee\ChangePermissionLevel;

class AdminEmployeePermissionController extends Controller
{
    /**
     * Show the Change permission view.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return mixed
     */
    public function show(Request $request, int $companyId, int $employeeId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        if ($loggedCompany->id != $companyId) {
            return redirect('/home');
        }

        if ($employeeId == $loggedEmployee->id) {
            return redirect('/home');
        }

        try {
            $employee = Employee::where('company_id', $loggedCompany->id)
                ->findOrFail($employeeId);
        } catch (ModelNotFoundException $e) {
            return redirect('/home');
        }

        return Inertia::render('Adminland/Employee/Permission/Index', [
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->name,
                'permission_level' => $employee->permission_level,
            ],
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }

    /**
     * Change permission.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'permission_level' => (int) $request->input('permission_level'),
        ];

        (new ChangePermissionLevel)->execute($data);

        return response()->json([
            true,
        ], 200);
    }
}
