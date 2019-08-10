<?php

namespace App\Services\Company\Employee\Manager;

use App\Models\User\User;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\DirectReport;

class UnassignManager extends BaseService
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
            'manager_id' => 'required|integer|exists:employees,id',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Remove a manager for the given employee.
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
        $manager = Employee::where('company_id', $data['company_id'])
            ->findOrFail($data['manager_id']);
        $directReport = DirectReport::where('company_id', $data['company_id'])
            ->where('employee_id', $data['employee_id'])
            ->where('manager_id', $data['manager_id'])
            ->firstOrFail();

        $directReport->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'manager_unassigned',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'manager_id' => $manager->id,
                'manager_name' => $manager->name,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        $this->logInEmployeeLogs($data, $author, $manager, $employee);

        return $manager;
    }

    /**
     * Log the information in the Employee log table.
     * Unassigning a manager affects two people: the manager and the employee.
     * Therefore we need two logs.
     *
     * @param array $data
     * @param User $author
     * @param Employee $manager
     * @param Employee $employee
     * @return void
     */
    private function logInEmployeeLogs(array $data, User $author, Employee $manager, Employee $employee) : void
    {
        // Log information about the employee having a manager assigned
        LogEmployeeAudit::dispatch([
            'company_id' => $data['company_id'],
            'employee_id' => $data['employee_id'],
            'action' => 'manager_unassigned',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'manager_id' => $manager->id,
                'manager_name' => $manager->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        // Log information about the manager having assigned a direct report
        LogEmployeeAudit::dispatch([
            'company_id' => $data['company_id'],
            'employee_id' => $data['employee_id'],
            'action' => 'direct_report_unassigned',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'direct_report_id' => $employee->id,
                'direct_report_name' => $employee->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);
    }
}
