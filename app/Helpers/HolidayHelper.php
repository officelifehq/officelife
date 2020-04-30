<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\Company\CompanyCalendar;
use App\Models\Company\CompanyPTOPolicy;

class HolidayHelper
{
    /**
     * Return the number of holidays an employee earns each month.
     *
     * @param Employee $employee
     *
     * @return float
     */
    public static function getHolidaysEarnedEachMonth(Employee $employee): float
    {
        return $employee->amount_of_allowed_holidays / 12;
    }

    /**
     * Return the number of days that an employee will earn as holidays from
     * today til the end of the year.
     *
     * @param CompanyPTOPolicy $ptoPolicy
     * @param Employee $employee
     *
     * @return float
     */
    public static function getNumberOfDaysLeftToEarn(CompanyPTOPolicy $ptoPolicy, Employee $employee): float
    {
        $totalNumberOfWorkedDaysInYear = $ptoPolicy->total_worked_days;
        $numberHolidaysEarnEachWorkedDay = $employee->amount_of_allowed_holidays / $totalNumberOfWorkedDaysInYear;

        $numberOfWorkedDaysLeftInYear = DB::table('company_calendars')
            ->where('company_pto_policy_id', $ptoPolicy->id)
            ->where('day', '>', Carbon::now()->format('Y-m-d'))
            ->where('is_worked', true)
            ->count();

        $numberOfDaysLeftToEarn = $numberOfWorkedDaysLeftInYear * $numberHolidaysEarnEachWorkedDay;

        return $numberOfDaysLeftToEarn;
    }

    /**
     * Return the number of holidays an employee earns each day.
     *
     * @param CompanyPTOPolicy $ptoPolicy
     * @param Employee $employee
     *
     * @return float
     */
    public static function getHolidaysEarnedEachDay(CompanyPTOPolicy $ptoPolicy, Employee $employee): float
    {
        $numberOfDaysWorkedInYear = $ptoPolicy->total_worked_days;

        return round(1 * $employee->amount_of_allowed_holidays / $numberOfDaysWorkedInYear, 3);
    }

    /**
     * Check if the date is considered off in the company.
     *
     * @param CompanyPTOPolicy $ptoPolicy
     * @param Carbon $date
     *
     * @return bool
     */
    public static function isDayWorkedForCompany(CompanyPTOPolicy $ptoPolicy, Carbon $date): ?bool
    {
        $day = CompanyCalendar::where('company_pto_policy_id', $ptoPolicy->id)
            ->where('day', $date->format('Y-m-d'))
            ->firstOrFail();

        return $day->is_worked;
    }
}
