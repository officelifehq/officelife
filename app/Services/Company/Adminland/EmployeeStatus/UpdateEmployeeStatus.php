<?php

namespace App\Services\Company\Adminland\EmployeeStatus;

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
    public function rules() : array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:users,id',
            'employee_status_id' => 'required|integer|exists:employee_statuses,id',
            'name' => 'required|string|max:255',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Update an employee status.
     *
     * @param array $data
     * @return EmployeeStatus
     */
    public function execute(array $data) : EmployeeStatus
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr')
        );

        $employeeStatus = EmployeeStatus::where('company_id', $data['company_id'])
            ->findOrFail($data['employee_status_id']);

        $oldStatusName = $employeeStatus->name;

        $employeeStatus->update([
            'name' => $data['name'],
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_status_updated',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_status_id' => $employeeStatus->id,
                'employee_status_old_name' => $oldStatusName,
                'employee_status_new_name' => $data['name'],
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $employeeStatus;
    }
}
