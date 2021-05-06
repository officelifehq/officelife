<?php

namespace App\Http\ViewHelpers\Dashboard;

use App\Helpers\ImageHelper;
use App\Models\Company\Employee;

class DashboardViewHelper
{
    /**
     * Get information about the employee.
     *
     * @param Employee $employee
     * @param string $currentTab
     * @return array|null
     */
    public static function information(Employee $employee, string $currentTab): ?array
    {
        return [
            'id' => $employee->id,
            'name' => $employee->name,
            'avatar' => ImageHelper::getAvatar($employee, 55),
            'dashboard_view' => $currentTab,
            'can_manage_expenses' => $employee->can_manage_expenses,
            'is_manager' => $employee->directReports->count() > 0,
            'can_manage_hr' => $employee->permission_level <= config('officelife.permission_level.hr'),
            'url' => route('employees.show', [
                'company' => $employee->company,
                'employee' => $employee,
            ]),
        ];
    }
}
