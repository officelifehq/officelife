<?php

namespace App\Http\Controllers;

use App\Models\User\User;
use App\Models\Company\Employee;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Check if the user has the permission to view the page.
     * It is used to restrict what an employee can see. It checks
     * if the user is either the employee being seen, or a person with at least
     * the HR role.
     *
     * @param int $userId
     * @param int $companyId
     * @param int $employeeId
     * @param int $requiredPermissionLevel
     * @return User
     */
    public function validateAccess(int $userId, int $companyId, int $employeeId, int $requiredPermissionLevel) : User
    {
        $employee = Employee::where('user_id', $userId)
            ->where('company_id', $companyId)
            ->firstOrFail();

        if ($employee->id == $employeeId) {
            return $employee->user;
        }

        if ($employee->id != $employeeId && $requiredPermissionLevel < $employee->permission_level) {
            throw new NotEnoughPermissionException;
        }

        return $employee->user;
    }
}
