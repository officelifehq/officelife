<?php

namespace App\Services\Company\Employee\Position;

use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Services\Company\Employee\LogEmployeeAction;
use App\Services\Company\Adminland\Company\LogAuditAction;

class RemovePositionFromEmployee extends BaseService
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
            'author_id' => 'required|integer|exists:users,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Remove an employee's position.
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
            config('homas.authorizations.hr')
        );

        $employee = Employee::where('company_id', $data['company_id'])
            ->findOrFail($data['employee_id']);

        $position = $employee->position;

        $employee->position_id = null;
        $employee->save();

        (new LogEmployeeAction)->execute([
            'company_id' => $data['company_id'],
            'employee_id' => $data['employee_id'],
            'action' => 'position_removed',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'position_id' => $position->id,
                'position_title' => $position->title,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        (new LogAuditAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'position_removed',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'position_id' => $position->id,
                'position_title' => $position->title,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $employee;
    }
}
