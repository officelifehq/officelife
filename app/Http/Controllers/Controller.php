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

    public User $authenticatedUser;

    public int $restrictedToCompanyId;

    public Employee $restrictedToEmployee;

    public int $requiredPermissionLevel;

    /**
     * Check if the user has the permission to view the page.
     * It is used to restrict what an employee can see. It checks
     * if the user is either the employee being seen, or a person with at least
     * the HR role.
     *
     * @return User
     */
    public function canAccessCurrentPage(): User
    {
        $employee = Employee::where('user_id', $this->authenticatedUser->id)
            ->where('company_id', $this->restrictedToCompanyId)
            ->firstOrFail();

        if ($employee->id == $this->restrictedToEmployee->id) {
            return $employee->user;
        }

        if ($employee->id != $this->restrictedToEmployee->id && $this->requiredPermissionLevel < $employee->permission_level) {
            throw new NotEnoughPermissionException;
        }

        return $employee->user;
    }

    public function asUser(User $user): self
    {
        $this->authenticatedUser = $user;
        return $this;
    }

    public function forEmployee(Employee $employee): self
    {
        $this->restrictedToEmployee = $employee;
        return $this;
    }

    public function forCompanyId(int $companyId): self
    {
        $this->restrictedToCompanyId = $companyId;
        return $this;
    }

    public function asPermissionLevel(int $permissionLevel): self
    {
        $this->requiredPermissionLevel = $permissionLevel;
        return $this;
    }
}
