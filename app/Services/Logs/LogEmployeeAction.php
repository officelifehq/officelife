<?php

namespace App\Services\Logs;

use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\EmployeeLog;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LogEmployeeAction extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:employees,id',
            'author_name' => 'required|string|max:255',
            'audited_at' => 'required|date',
            'employee_id' => 'required|integer|exists:employees,id',
            'action' => 'required|string|max:255',
            'objects' => 'required|json',
            'ip_address' => 'nullable|ipv4',
        ];
    }

    /**
     * Log an action that happened to the employee.
     * This also creates an audit log.
     *
     * @param array $data
     *
     * @return EmployeeLog
     */
    public function execute(array $data): EmployeeLog
    {
        $this->validateRules($data);

        $author = Employee::findOrFail($data['author_id']);
        $employee = Employee::findOrFail($data['employee_id']);

        if ($author->company_id != $employee->company_id) {
            throw new ModelNotFoundException();
        }

        return EmployeeLog::create([
            'employee_id' => $data['employee_id'],
            'author_id' => $data['author_id'],
            'author_name' => $data['author_name'],
            'audited_at' => $data['audited_at'],
            'action' => $data['action'],
            'objects' => $data['objects'],
            'ip_address' => $this->valueOrNull($data, 'ip_address'),
        ]);
    }
}
