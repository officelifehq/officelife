<?php

namespace App\Services\Company\Employee\Holiday;

use Exception;
use Carbon\Carbon;
use App\Services\BaseService;
use Illuminate\Validation\Rule;
use App\Models\Company\Employee;
use App\Models\Company\CompanyCalendar;
use App\Models\Company\CompanyPTOPolicy;
use App\Models\Company\EmployeePlannedHoliday;

class CreateTimeOff extends BaseService
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

        // grab the PTO policy and check wether this day is a worked day or not
        $suggestedDate = Carbon::parse($data['date']);
        $ptoPolicy = $employee->company->getCurrentPTOPolicy();
        if (!$this->isDayWorkedForCompany($ptoPolicy, $suggestedDate)) {
            throw new Exception();
        }

        // check if an holiday already exists for this day
        // If the date is already taken as a planned holiday in full, we can't take
        // this day as it’s already taken.
        // If the date is already taken but as half, it means we can take it but
        // only as a half day.
        $existingPlannedHoliday = $this->getExistingPlannedHoliday($employee, $suggestedDate);
        if ($existingPlannedHoliday) {
            $plannedHoliday = $this->updateExistingPlannedHoliday($existingPlannedHoliday, $data);
        } else {
            $plannedHoliday = $this->createPlannedHoliday($data, $suggestedDate);
        }

        return $plannedHoliday;
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
        $day = CompanyCalendar::where('company_pto_policy_id', $ptoPolicy->id)
            ->where('day', $date->format('Y-m-d'))
            ->firstOrFail();

        return $day->is_worked;
    }

    /**
     * Get the planned holiday object for this date, if it already exists.
     *
     * @param Employee $employee
     * @param Carbon $date
     * @return EmployeePlannedHoliday
     */
    private function getExistingPlannedHoliday(Employee $employee, Carbon $date)
    {
        $holiday = EmployeePlannedHoliday::where('employee_id', $employee->id)
            ->where('planned_date', $date->format('Y-m-d'))
            ->first();

        return $holiday;
    }

    /**
     * Update an existing planned holiday with new details.
     *
     * @param EmployeePlannedHoliday $holiday
     * @param array $data
     * @return EmployeePlannedHoliday
     */
    private function updateExistingPlannedHoliday(EmployeePlannedHoliday $holiday, array $data) : EmployeePlannedHoliday
    {
        // here we can only accept to log a half day of time off
        if ($holiday->full) {
            throw new Exception();
        }

        if ($data['full']) {
            throw new Exception();
        }

        // all conditions are good, we can update the day as a full holiday
        $holiday->full = true;
        $holiday->save();

        return $holiday;
    }

    /**
     * Create a new planned holiday.
     *
     * @param array $data
     * @param Carbon $date
     * @return EmployeePlannedHoliday
     */
    private function createPlannedHoliday(array $data, Carbon $date) : EmployeePlannedHoliday
    {
        return EmployeePlannedHoliday::create([
            'employee_id' => $data['employee_id'],
            'planned_date' => $date,
            'type' => $data['type'],
            'full' => $data['full'],
        ]);
    }
}
