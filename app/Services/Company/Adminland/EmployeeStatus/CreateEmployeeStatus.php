<?php

namespace App\Services\Company\Adminland\EmployeeStatus;

use App\Jobs\LogAccountAudit;
use App\Models\Company\EmployeeStatus;
use App\Services\BaseService;
use Carbon\Carbon;

class CreateEmployeeStatus extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ];
    }

    /**
     * Create an employee status.
     */
    public function execute(array $data): EmployeeStatus
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $employeeStatus = EmployeeStatus::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'type' => $data['type'],
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_status_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_status_id' => $employeeStatus->id,
                'employee_status_name' => $employeeStatus->name,
            ]),
        ])->onQueue('low');

        return $employeeStatus;
    }
}
