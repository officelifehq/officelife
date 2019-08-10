<?php

namespace App\Services\Company\Adminland\Employee;

use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;

class UpdateHiringInformation extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:users,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'hired_at' => 'required|date',
        ];
    }

    /**
     * Update the hiring information about an employee.
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
            'hired_at' => $data['hired_at'],
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_updated_hiring_information',
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
