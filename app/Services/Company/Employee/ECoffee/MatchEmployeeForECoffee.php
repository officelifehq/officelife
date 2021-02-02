<?php

namespace App\Services\Company\Employee\Description;

use App\Services\BaseService;
use App\Models\Company\Employee;

class MatchEmployeeForECoffee extends BaseService
{
    private Employee $employee;

    private array $data;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'batch_number' => 'required|integer',
        ];
    }

    /**
     * Match an employee with another for the e-coffee process.
     * The employee should not have been matched with the other employee
     * in the last 3 batches, if possible.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->getEmployeeToMatchWith();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->employee = Employee::where('company_id', $this->data['company_id'])
            ->findOrFail('employee_id', $this->data['employee_id']);
    }
}
