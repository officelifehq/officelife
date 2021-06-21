<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Models\Company\Company;
use Illuminate\Support\Collection;
use App\Models\Company\EmployeeStatus;

class AdminEmployeeStatusViewHelper
{
    /**
     * Collection containing information about all the employee statuses in the
     * account.
     *
     * @param Company $company
     * @return Collection
     */
    public static function index(Company $company): Collection
    {
        return $company->employeeStatuses()
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($status) {
                return self::show($status);
            });
    }

    /**
     * Get information about one employee status.
     *
     * @param EmployeeStatus $employeeStatus
     * @return array
     */
    public static function show(EmployeeStatus $employeeStatus): array
    {
        return [
            'id' => $employeeStatus->id,
            'name' => $employeeStatus->name,
            'type' => $employeeStatus->type,
        ];
    }
}
