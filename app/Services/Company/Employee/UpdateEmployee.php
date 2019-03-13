<?php

namespace App\Services\Company\Employee;

use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Services\Company\Company\LogAction;

class UpdateEmployee extends BaseService
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
            'email' => 'required|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'nullable|date',
        ];
    }

    /**
     * Update an employee.
     *
     * @param array $data
     * @return Employee
     */
    public function execute(array $data) : Employee
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr')
        );

        $employee = Employee::where('company_id', $data['company_id'])
            ->findOrFail($data['employee_id']);

        $employee->update([
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'birthdate' => $this->nullOrDate($data, 'birthdate'),
        ]);

        (new LogAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'employee_updated',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
            ]),
        ]);

        return $employee;
    }
}
