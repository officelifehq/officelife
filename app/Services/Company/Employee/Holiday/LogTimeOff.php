<?php

namespace App\Services\Company\Employee\Morale;

use Exception;
use Carbon\Carbon;
use DomainException;
use App\Services\BaseService;
use Illuminate\Validation\Rule;
use App\Models\Company\Employee;
use App\Models\Company\CompanyCalendar;
use App\Models\Company\CompanyPTOPolicy;
use App\Models\Company\EmployeePlannedHoliday;
use App\Exceptions\StartDateAfterEndDateException;

class LogTimeOff extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'date' => 'required|date_format:Y-m-d',
            'type' => 'required',
                Rule::in([
                    'holiday',
                    'sick',
                    'pto',
                ]),
            'full' => 'required|boolean',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Log a time off for the given employee.
     *
     * @param array $data
     * @return EmployeePlannedHoliday
     */
    public function execute(array $data) : EmployeePlannedHoliday
    {
        $this->validate($data);

        $employee = Employee::findOrFail($data['employee_id']);

        $author = $this->validatePermissions(
            $data['author_id'],
            $employee->company_id,
            config('villagers.authorizations.hr'),
            $data['employee_id']
        );

        // $this->checkDates($data);

        // grab the PTO policy and check wether this day is a worked day or not
        $suggestedDate = Carbon::parse($data['date']);
        $ptoPolicy = $employee->company->getCurrentPTOPolicy();
        if (!$this->isDayWorkedForCompany($ptoPolicy, $suggestedDate)) {
            throw new DomainException();
        }

        // check if an holiday already exists for this day
        $existingPlannedHoliday = $this->getExistingPlannedHoliday($employee, $suggestedDate);
        if ($existingPlannedHoliday) {
            // here we can only accept to log a half day of time off
            $plannedHoliday = $existingPlannedHoliday;
            if ($plannedHoliday->full) {
                throw new Exception();
            }

            if ($data['full']) {
                throw new Exception();
            }

            // all conditions are good, we can update the day as a full holiday
            $plannedHoliday->full = true;
            $plannedHoliday->save();
        }

        // case if the holiday doesn't already exist
        if (!$existingPlannedHoliday) {
            $plannedHoliday = EmployeePlannedHoliday::create([
                'employee_id' => $data['employee_id'],
                'planned_date' => $suggestedDate,
                'type' => $data['type'],
                'full' => $data['full'],
            ]);
        }

        return $plannedHoliday;
    }

    /**
     * Check if the start date is before the end date.
     *
     * @param array $data
     * @return void
     */
    private function checkDates(array $data)
    {
        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);

        if ($end->lessThan($start)) {
            throw new StartDateAfterEndDateException();
        }
    }

    /**
     * Check if the date is considered off in the company.
     *
     * @param CompanyPTOPolicy $ptoPolicy
     * @param Carbon $date
     * @return boolean
     */
    private function isDayWorkedForCompany(CompanyPTOPolicy $ptoPolicy, Carbon $date) : bool
    {
        $day = CompanyCalendar::where('company_pto_policy_id', $ptoPolicy)
            ->where('day', $date->format('Y-m-d'))
            ->firstOrFail();

        return $day->is_worked;
    }

    private function getExistingPlannedHoliday(Employee $employee, Carbon $date)
    {
        $holiday = EmployeePlannedHoliday::where('employee_id', $employee->id)
            ->where('planned_date', $date)
            ->first();

        return $holiday;
    }

    /**
     * Check if the date is available to be marked as time off.
     * If the date is already taken as a planned holiday in full, we can't take
     * this day as it’s already taken.
     * If the date is already taken but as half, it means we can take it but
     * only as a half day.
     * If the date is not used at all, we can take it.
     *
     * @param Employee $employee
     * @param Carbon $date
     * @return boolean
     */
    private function isDateAvailable(EmployeePlannedHoliday $planned, array $data)
    {
        if ($planned->full) {
            throw new Exception();
        }

        if ($data['full']) {
            throw new Exception();
        }

        return true;
    }
}
