<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class AdminEmployeeViewHelper
{
    /**
     * Get all the statistics about the employees.
     *
     * @param EloquentCollection $employees
     * @param Company $company
     * @return array
     */
    public static function index(EloquentCollection $employees, Company $company): array
    {
        $numberOfLockedAccounts = $employees->filter(function ($employee) {
            return $employee->locked;
        });

        $numberOfActiveAccounts = $employees->filter(function ($employee) {
            return ! $employee->locked;
        });

        $numberOfEmployees = $employees->count();

        $numberOfEmployeesWithoutHireDate = $employees->filter(function ($employee) {
            return ! $employee->hired_at;
        });

        $stats = [
            'number_of_locked_accounts' => $numberOfLockedAccounts->count(),
            'number_of_active_accounts' => $numberOfActiveAccounts->count(),
            'number_of_employees' => $numberOfEmployees,
            'number_of_employees_without_hire_date' => $numberOfEmployeesWithoutHireDate->count(),
            'url_all' => route('account.employees.all', [
                'company' => $company,
            ]),
            'url_active' => route('account.employees.active', [
                'company' => $company,
            ]),
            'url_locked' => route('account.employees.locked', [
                'company' => $company,
            ]),
            'url_no_hiring_date' => route('account.employees.no_hiring_date', [
                'company' => $company,
            ]),
            'url_new' => route('account.employees.new', [
                'company' => $company,
            ]),
            'url_upload' => route('account.employees.upload', [
                'company' => $company,
            ]),
            'url_upload_archive' => route('account.employees.upload.archive', [
                'company' => $company,
            ]),
        ];

        return $stats;
    }

    /**
     * Get information about all the employees in the company.
     */
    public static function all(EloquentCollection $employees, Company $company): Collection
    {
        $employeesCollection = self::getCollectionOfEmployees($employees, $company);

        return $employeesCollection;
    }

    private static function getCollectionOfEmployees(Collection $employees, Company $company): Collection
    {
        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'permission_level' => $employee->permission_level,
                'avatar' => ImageHelper::getAvatar($employee, 64),
                'invitation_link' => $employee->invitation_link,
                'invited' => (! $employee->invitation_used_at && $employee->invitation_link) === true,
                'has_user_account' => ($employee->invitation_used_at && $employee->invitation_link) === true,
                'locked' => $employee->locked,
                'url_view' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'url_delete' => route('account.delete', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'url_lock' => route('account.lock', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'url_invite' => route('account.employees.invite', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'url_unlock' => route('account.unlock', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'url_permission' => route('account.employees.permission', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ]);
        }

        return $employeesCollection;
    }
}
