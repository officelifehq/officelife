<?php

namespace App\Services\Company\Employee\Timesheet;

use Carbon\Carbon;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Carbon\Exceptions\InvalidDateException;

class CreateOrGetTimesheet extends BaseService
{
    private array $data;

    private Employee $employee;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'date' => 'required|date_format:Y-m-d',
        ];
    }

    /**
     * Create a timesheet for the employee for the week of the given date.
     * There can only be one timesheet per week, per employee. This service will
     * make sure that we don’t create two timesheets for the same week.
     * Timesheets will be created on the fly, when the employee consults the
     * details of a timesheet - so if a timesheet already exists in the given
     * week, we need to send this timesheet instead of creating a new one.
     *
     * @param array $data
     * @return Timesheet
     */
    public function execute(array $data): Timesheet
    {
        $this->data = $data;
        $this->validate();
        $timesheet = $this->createTimesheet();

        return $timesheet;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfEmployee($this->data['employee_id'])
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($this->data);
    }

    private function createTimesheet(): Timesheet
    {
        try {
            $date = Carbon::createFromFormat('Y-m-d', $this->data['date']);
        } catch (InvalidDateException $e) {
            throw new \Exception(trans('app.error_invalid_date'));
        }

        $startOfWeek = $date->copy()->startOfWeek();
        $endOfWeek = $date->copy()->endOfWeek();

        // is there an existing timesheet for this date?
        $timesheet = Timesheet::where('company_id', $this->data['company_id'])
            ->where('employee_id', $this->data['employee_id'])
            ->where('started_at', $startOfWeek)
            ->where('ended_at', $endOfWeek)
            ->first();

        if ($timesheet) {
            return $timesheet;
        }

        // no existing timesheet - we need to create a timesheet
        return Timesheet::create([
            'company_id' => $this->data['company_id'],
            'employee_id' => $this->employee->id,
            'started_at' => $startOfWeek,
            'ended_at' => $endOfWeek,
            'status' => Timesheet::OPEN,
        ]);
    }
}
