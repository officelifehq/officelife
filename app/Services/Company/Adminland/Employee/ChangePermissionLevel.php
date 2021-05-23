<?php

namespace App\Services\Company\Adminland\Employee;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;

class ChangePermissionLevel extends BaseService
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
            'employee_id' => 'required|integer|exists:employees,id',
            'permission_level' => 'required|integer',
        ];
    }

    /**
     * Change permission for the given employee.
     *
     * @param array $data
     *
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $employee = $this->validateEmployeeBelongsToCompany($data);

        $oldPermission = $employee->permission_level;

        Employee::where('id', $employee->id)->update([
            'permission_level' => $data['permission_level'],
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'permission_changed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'old_permission' => $oldPermission,
                'new_permission' => $data['permission_level'],
            ]),
        ])->onQueue('low');

        return $employee;
    }
}
