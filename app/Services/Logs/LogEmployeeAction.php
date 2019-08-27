<?php

namespace App\Services\Logs;

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
    public function rules() : array
    {
        return [
            'author_id' => 'required|integer|exists:employees,id',
            'author_name' => 'required|string|max:255',
            'audited_at' => 'required|date',
            'employee_id' => 'required|integer|exists:employees,id',
            'action' => 'required|string|max:255',
            'objects' => 'required|json',
            'ip_address' => 'nullable|ipv4',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Log an action that happened to the employee.
     * This also creates an audit log.
     *
     * @param array $data
     * @return EmployeeLog
     */
    public function execute(array $data) : EmployeeLog
    {
        $this->validate($data);

        return EmployeeLog::create([
            'employee_id' => $data['employee_id'],
            'author_id' => $data['author_id'],
            'author_name' => $data['author_name'],
            'audited_at' => $data['audited_at'],
            'action' => $data['action'],
            'objects' => $data['objects'],
            'ip_address' => $this->nullOrValue($data, 'ip_address'),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);
    }
}
