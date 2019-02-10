<?php

namespace App\Services\Company\Employee;

use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Services\Company\Company\LogAction;

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
        ];
    }

    /**
     * Delete an employee.
     *
     * @param array $data
     * @return void
     */
    public function execute(array $data)
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

        (new LogAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'employee_destroyed',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_id' => $employee->id,
                'employee_first_name' => $employee->identity->{'first_name'},
                'employee_last_name' => $employee->identity->{'last_name'},
            ]),
        ]);
    }
}
