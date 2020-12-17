<?php

namespace App\Services\Company\Employee\Contract;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Carbon\Exceptions\InvalidDateException;

class SetContractRenewalDate extends BaseService
{
    protected Employee $employee;

    protected array $data;

    protected Carbon $date;

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
     * Set the contract renewal date of an employee.
     */
    public function execute(array $data): Employee
    {
        $this->data = $data;

        $this->validate();
        $this->checkDateValidity();
        $this->save();
        $this->log();

        return $this->employee;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
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
        $this->employee->contract_renewed_at = $this->date;
        $this->employee->save();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'employee_contract_renewed_at_set',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
                'contract_renewed_at' => $this->date->format('Y-m-d'),
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->data['employee_id'],
            'action' => 'contract_renewed_at_set',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'contract_renewed_at' => $this->date->format('Y-m-d'),
            ]),
        ])->onQueue('low');
    }
}
