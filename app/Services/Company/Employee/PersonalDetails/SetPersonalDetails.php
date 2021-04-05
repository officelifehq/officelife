<?php

namespace App\Services\Company\Employee\PersonalDetails;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Exceptions\NotEnoughPermissionException;

class SetPersonalDetails extends BaseService
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
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email:rfc|max:255',
            'phone' => 'nullable|max:255',
            'timezone' => 'required|string|max:255',
        ];
    }

    /**
     * Set the name and email address of an employee.
     *
     * @param array $data
     * @throws NotEnoughPermissionException
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->data = $data;
        $this->validate();
        $this->save();
        $this->log();

        return $this->employee->refresh();
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

    private function save(): void
    {
        $this->employee->first_name = $this->data['first_name'];
        $this->employee->last_name = $this->data['last_name'];
        $this->employee->email = $this->data['email'];
        $this->employee->phone_number = $this->data['phone'];
        $this->employee->timezone = $this->data['timezone'];
        $this->employee->save();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'employee_personal_details_set',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
                'employee_email' => $this->employee->email,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->data['employee_id'],
            'action' => 'personal_details_set',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'name' => $this->employee->name,
                'email' => $this->employee->email,
            ]),
        ])->onQueue('low');
    }
}
