<?php

namespace App\Services\Company\Adminland\Employee;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;

class ChangePermission extends BaseService
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
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('officelife.authorizations.hr')
        );

        $employee = Employee::find($data['employee_id']);

        $oldPermission = $employee->permission_level;

        $employee->permission_level = $data['permission_level'];
        $employee->save();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'permission_changed',
            'author_id' => $author->id,
            'author_name' => $author->name,
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
