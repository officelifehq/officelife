<?php

namespace App\Services\Company\Employee\EmployeeStatus;

use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;

class RemoveEmployeeStatusFromEmployee extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() :array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:users,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Remove an employee's status.
     *
     * @param array $data
     * @return Employee
     */
    public function execute(array $data) :Employee
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr')
        );

        $employee = Employee::where('company_id', $data['company_id'])
            ->findOrFail($data['employee_id']);

        $employeeStatus = $employee->status;

        $employee->employee_status_id = null;
        $employee->save();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_status_removed',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'employee_status_id' => $employeeStatus->id,
                'employee_status_name' => $employeeStatus->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $data['employee_id'],
            'action' => 'employee_status_removed',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_status_id' => $employeeStatus->id,
                'employee_status_name' => $employeeStatus->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        $employee->refresh();

        return $employee;
    }
}
