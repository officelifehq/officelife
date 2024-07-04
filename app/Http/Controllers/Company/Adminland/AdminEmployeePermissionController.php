<?php

namespace App\Http\Controllers\Company\Adminland;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\Employee;
use App\Services\Company\Adminland\Employee\ChangePermissionLevel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminEmployeePermissionController extends Controller
{
    /**
     * Show the Change permission view.
     *
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
