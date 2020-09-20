<?php

namespace App\Services\Company\Employee\Manager;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Exceptions\SameIdsException;
use App\Models\Company\DirectReport;
use App\Exceptions\NotEnoughPermissionException;

class AssignManager extends BaseService
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
            'manager_id' => 'required|integer|exists:employees,id',
        ];
    }

    /**
     * Set an employee as being the manager of the given employee.
     *
     * @param array $data
     *
     * @throws SameIdsException
     * @throws NotEnoughPermissionException
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

        $this->log($data, $manager, $employee);

        return $manager;
    }

    private function log(array $data, Employee $manager, Employee $employee): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'manager_assigned',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'manager_id' => $manager->id,
                'manager_name' => $manager->name,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
            ]),
        ])->onQueue('low');

        // Log information about the employee having a manager assigned
        LogEmployeeAudit::dispatch([
            'company_id' => $data['company_id'],
            'employee_id' => $data['employee_id'],
            'action' => 'manager_assigned',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'manager_id' => $manager->id,
                'manager_name' => $manager->name,
            ]),
        ])->onQueue('low');

        // Log information about the manager having assigned a direct report
        LogEmployeeAudit::dispatch([
            'employee_id' => $manager->id,
            'action' => 'direct_report_assigned',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'direct_report_id' => $employee->id,
                'direct_report_name' => $employee->name,
            ]),
        ])->onQueue('low');
    }
}
