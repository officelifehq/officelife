<?php

namespace App\Helpers;

use App\Models\Company\Employee;
use App\Models\Company\CompanyPTOPolicy;

class HolidayHelper
{
    /**
     * Return the number of holidays an employee earns each month.
     *
     * @param Employee $employee
     * @return float
     */
    public static function getHolidaysEarnedEachMonth(Employee $employee) : float
    {
        return $employee->amount_of_allowed_holidays / 12;
    }

    /**
     * Return the number of days that an employee will earn as holidays from
     * today til the end of the year.
     *
     * @param CompanyPTOPolicy $ptoPolicy
     * @param Employee $employee
     * @return float
     */
    public static function getNumberOfDaysLeftToEarn(CompanyPTOPolicy $ptoPolicy, Employee $employee) : float
    {
        $workedDays = $ptoPolicy->total_worked_days;
        $totalNumberOfHolidays = $ptoPolicy->default_amount_of_allowed_holidays;

        // n$leftDaysToEarn = $employee->
    }
}
