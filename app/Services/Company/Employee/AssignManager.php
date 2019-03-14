<?php

namespace App\Services\Company\Employee;

use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Exceptions\SameIdsException;
use App\Models\Company\DirectReport;
use App\Services\Company\Company\LogAction;

class AssignManager extends BaseService
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
            'manager_id' => 'required|integer|exists:employees,id',
        ];
    }

    /**
     * Set an employee as being the manager of the given employee.
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
        $manager = Employee::where('company_id', $data['company_id'])
            ->findOrFail($data['manager_id']);

        if ($manager->id == $employee->id) {
            throw new SameIdsException;
        }

        DirectReport::create([
            'company_id' => $data['company_id'],
            'manager_id' => $data['manager_id'],
            'employee_id' => $data['employee_id'],
        ]);

        (new LogAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'manager_assigned',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'manager_id' => $manager->id,
                'manager_name' => $manager->name,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
            ]),
        ]);

        return $manager;
    }
}
