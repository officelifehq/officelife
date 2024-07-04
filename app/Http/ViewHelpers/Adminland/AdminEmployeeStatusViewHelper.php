<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Models\Company\Company;
use App\Models\Company\EmployeeStatus;
use Illuminate\Support\Collection;

class AdminEmployeeStatusViewHelper
{
    /**
     * Collection containing information about all the employee statuses in the
     * account.
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
