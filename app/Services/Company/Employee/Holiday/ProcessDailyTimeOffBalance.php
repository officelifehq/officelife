<?php

namespace App\Services\Company\Employee\Holiday;

use Carbon\Carbon;
use App\Services\BaseService;
use App\Helpers\HolidayHelper;
use App\Models\Company\Employee;
use App\Models\Company\EmployeeDailyCalendarEntry;

class ProcessDailyTimeOffBalance extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'required|integer|exists:employees,id',
            'date' => 'required|date_format:Y-m-d',
        ];
    }

    /**
     * Calculate, for the given date, the time off balance for the given
     * employee.
     * This is an automated process, triggered via a cron. Therefore we don't
     * need to validate the service before processing it.
     *
     * @param array $data
     *
     * @return EmployeeDailyCalendarEntry
     */
    public function execute(array $data): EmployeeDailyCalendarEntry
    {
        $this->validateRules($data);

        $employee = Employee::findOrFail($data['employee_id']);
        $date = Carbon::parse($data['date']);

        $ptoPolicy = $employee->company->getCurrentPTOPolicy();
        $holidaysEarnedEachDay = HolidayHelper::getHolidaysEarnedEachDay($ptoPolicy, $employee);

        if (HolidayHelper::isDayWorkedForCompany($ptoPolicy, $date)) {
            $newBalance = $employee->holiday_balance + $holidaysEarnedEachDay;
        } else {
            $newBalance = $employee->holiday_balance;
        }

        $employeeDailyCalendarEntry = EmployeeDailyCalendarEntry::create([
            'employee_id' => $employee->id,
            'log_date' => $date,
            'new_balance' => $newBalance,
            'daily_accrued_amount' => $holidaysEarnedEachDay,
            'current_holidays_per_year' => $employee->amount_of_allowed_holidays,
            'default_amount_of_allowed_holidays_in_company' => $ptoPolicy->default_amount_of_allowed_holidays,
        ]);

        $employee->holiday_balance = $newBalance;
        $employee->save();

        return $employeeDailyCalendarEntry;
    }
}
