<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\TimezoneHelper;
use App\Models\Company\Employee;

class EmployeeEditViewHelper
{
    /**
     * Get information about the employee being edited.
     *
     * @param Employee $employee
     * @return array
     */
    public static function show(Employee $employee): array
    {
        return [
            'id' => $employee->id,
            'first_name' => $employee->first_name,
            'last_name' => $employee->last_name,
            'name' => $employee->name,
            'email' => $employee->email,
            'phone' => $employee->phone_number,
            'birthdate' => (! $employee->birthdate) ? null : [
                'year' => $employee->birthdate->year,
                'month' => $employee->birthdate->month,
                'day' => $employee->birthdate->day,
            ],
            'hired_at' => (! $employee->hired_at) ? null : [
                'year' => $employee->hired_at->year,
                'month' => $employee->hired_at->month,
                'day' => $employee->hired_at->day,
            ],
            'twitter_handle' => $employee->twitter_handle,
            'slack_handle' => $employee->slack_handle,
            'max_year' => Carbon::now()->year,
            'timezone' => TimezoneHelper::getTimezoneKeyValue($employee->timezone),
        ];
    }
}
