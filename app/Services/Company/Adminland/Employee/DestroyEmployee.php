<?php

namespace App\Services\Company\Adminland\Employee;

use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Services\Company\Adminland\Company\LogAuditAction;

class DestroyEmployee extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'author_id' => 'required|integer|exists:users,id',
            'employee_id' => 'required|exists:employees,id|integer',
            'company_id' => 'required|exists:companies,id|integer',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Delete an employee.
     *
     * @param array $data
     * @return void
     */
    public function execute(array $data) : void
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.administrator')
        );

        $employee = Employee::where('company_id', $data['company_id'])
            ->findOrFail($data['employee_id']);

        $employee->delete();

        (new LogAuditAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'employee_destroyed',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);
    }
}
