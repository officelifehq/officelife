<?php

namespace App\Services\Employee;

use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\EmployeeLog;

class LogEmployeeAction extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'action' => 'required|string|max:255',
            'objects' => 'required|json',
            'ip_address' => 'nullable|ipv4',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Log an action that happened to the employee.
     *
     * @param array $data
     * @return EmployeeLog
     */
    public function execute(array $data): EmployeeLog
    {
        $this->validate($data);

        Employee::where('company_id', $data['company_id'])
            ->findOrFail($data['employee_id']);

        return EmployeeLog::create([
            'company_id' => $data['company_id'],
            'employee_id' => $data['employee_id'],
            'action' => $data['action'],
            'objects' => $data['objects'],
            'ip_address' => $this->nullOrValue($data, 'ip_address'),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);
    }
}
