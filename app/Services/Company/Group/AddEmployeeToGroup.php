<?php

namespace App\Services\Company\Group;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Services\QueuableService;
use App\Services\DispatchableService;

class AddEmployeeToGroup extends BaseService implements QueuableService
{
    use DispatchableService;

    private array $data;

    private Employee $employee;

    private Group $group;

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
            'group_id' => 'required|integer|exists:groups,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'role' => 'nullable|string',
        ];
    }

    /**
     * Initialize service.
     *
     * @param array $data
     * @return self
     */
    public function init(array $data = []): self
    {
        $this->data = $data;
        $this->validate();

        return $this;
    }

    /**
     * Add an employee to a group.
     *
     * @return void
     */
    public function handle(): void
    {
        [$employee, $group] = $this->validate();
        $this->employee = $employee;
        $this->group = $group;

        $this->attachEmployee();
        $this->log();
    }

    /**
     * Add an employee to a group.
     *
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->data = $data;
        $this->handle();

        return $this->employee;
    }

    private function validate(): array
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $employee = $this->validateEmployeeBelongsToCompany($this->data);

        $group = Group::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['group_id']);

        return [$employee, $group];
    }

    private function attachEmployee(): void
    {
        $this->group->employees()->syncWithoutDetaching([
            $this->data['employee_id'] => [
                'role' => $this->valueOrNull($this->data, 'role'),
            ],
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'employee_added_to_group',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'group_id' => $this->group->id,
                'group_name' => $this->group->name,
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'employee_added_to_group',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'group_id' => $this->group->id,
                'group_name' => $this->group->name,
            ]),
        ])->onQueue('low');
    }
}
