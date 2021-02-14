<?php

namespace App\Services\Company\Adminland\EmployeeStatus;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\EmployeeStatus;

class UpdateEmployeeStatus extends BaseService
{
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
            'employee_status_id' => 'required|integer|exists:employee_statuses,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ];
    }

    /**
     * Update an employee status.
     *
     * @param array $data
     *
     * @return EmployeeStatus
     */
    public function execute(array $data): EmployeeStatus
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $employeeStatus = EmployeeStatus::where('company_id', $data['company_id'])
            ->findOrFail($data['employee_status_id']);

        $oldStatusName = $employeeStatus->name;

        $employeeStatus->update([
            'name' => $data['name'],
            'type' => $data['type'],
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_status_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_status_id' => $employeeStatus->id,
                'employee_status_old_name' => $oldStatusName,
                'employee_status_new_name' => $data['name'],
            ]),
        ])->onQueue('low');

        return $employeeStatus;
    }
}
