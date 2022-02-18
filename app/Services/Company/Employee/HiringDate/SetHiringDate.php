<?php

namespace App\Services\Company\Employee\HiringDate;

use Carbon\Carbon;
use App\Models\Company\Flow;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Carbon\Exceptions\InvalidDateException;
use App\Services\Company\Adminland\Flow\ScheduleFlowsForEmployee;

class SetHiringDate extends BaseService
{
    private array $data;
    private Employee $employee;
    private Carbon $date;

    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'year' => 'required|integer',
            'month' => 'required|integer',
            'day' => 'required|integer',
        ];
    }

    /**
     * Set the hiring date of an employee.
     */
    public function execute(array $data): Employee
    {
        $this->data = $data;
        $this->validate();

        $this->checkDateValidity();
        $this->save();
        $this->log();
        $this->scheduleRelatedFlows();

        return $this->employee;
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

    private function checkDateValidity(): void
    {
        try {
            $this->date = Carbon::createSafe($this->data['year'], $this->data['month'], $this->data['day']);
            if ($this->date === false) {
                throw new InvalidDateException('', '');
            }
        } catch (InvalidDateException $e) {
            throw new \Exception(trans('app.error_invalid_date'));
        }
    }

    private function save(): void
    {
        $this->employee->hired_at = $this->date;
        $this->employee->save();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'employee_hiring_date_set',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
                'hiring_date' => $this->date->format('Y-m-d'),
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->data['employee_id'],
            'action' => 'hiring_date_set',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'hiring_date' => $this->date->format('Y-m-d'),
            ]),
        ])->onQueue('low');
    }

    /**
     * Scheduled related flows and actions, if any are defined in the account
     * about the hiring date.
     */
    private function scheduleRelatedFlows(): void
    {
        (new ScheduleFlowsForEmployee)->execute([
            'company_id' => $this->employee->company_id,
            'employee_id' => $this->employee->id,
            'trigger' => Flow::TRIGGER_HIRING_DATE,
        ]);
    }
}
