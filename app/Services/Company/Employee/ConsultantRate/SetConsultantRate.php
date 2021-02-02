<?php

namespace App\Services\Company\Employee\ConsultantRate;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\ConsultantRate;
use App\Models\Company\EmployeeStatus;
use App\Exceptions\NotConsultantException;

class SetConsultantRate extends BaseService
{
    protected Employee $employee;

    protected array $data;

    protected ConsultantRate $rate;

    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'rate' => 'required|integer',
        ];
    }

    /**
     * Set the consultant rate of an employee, if the employee has an Employee status marked as
     * External.
     */
    public function execute(array $data): ConsultantRate
    {
        $this->data = $data;

        $this->validate();
        $this->checkEmployeeHasExternalStatus();
        $this->setAllPreviousRatesAsInactive();
        $this->setRate();
        $this->log();

        return $this->rate;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfManager($this->data['author_id'], $this->data['employee_id'])
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($this->data);
    }

    private function checkEmployeeHasExternalStatus(): void
    {
        $status = $this->employee->status;
        if (! $status) {
            throw new NotConsultantException();
        }

        if ($status->type != EmployeeStatus::EXTERNAL) {
            throw new NotConsultantException();
        }
    }

    private function setAllPreviousRatesAsInactive(): void
    {
        ConsultantRate::where('company_id', $this->data['company_id'])
            ->where('employee_id', $this->data['employee_id'])
            ->update([
                'active' => false,
            ]);
    }

    private function setRate(): void
    {
        $this->rate = ConsultantRate::create([
            'company_id' => $this->data['company_id'],
            'employee_id' => $this->data['employee_id'],
            'rate' => $this->data['rate'],
            'active' => true,
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'consultant_rate_set',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
                'rate' => $this->data['rate'],
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->data['employee_id'],
            'action' => 'consultant_rate_set',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'rate' => $this->data['rate'],
            ]),
        ])->onQueue('low');
    }
}
