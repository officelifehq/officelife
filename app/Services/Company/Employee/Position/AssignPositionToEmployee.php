<?php

namespace App\Services\Company\Employee\Position;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\EmployeePositionHistory;

class AssignPositionToEmployee extends BaseService
{
    private Employee $employee;
    private array $data;
    private Position $position;
    private int $previousPositionId;

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
            'position_id' => 'required|integer|exists:positions,id',
        ];
    }

    /**
     * Set an employee's position.
     *
     * @param array $data
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->data = $data;
        $this->validate();
        $this->updateEmployee();
        $this->addEmployeePositionHistoryEntry();
        $this->log();

        return $this->employee;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($this->data);

        $this->position = Position::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['position_id']);

        $this->previousPositionId = 0;
        if ($this->employee->position_id) {
            $this->previousPositionId = $this->employee->position_id;
        }
    }

    private function updateEmployee(): void
    {
        $this->employee->position_id = $this->position->id;
        $this->employee->save();
    }

    private function addEmployeePositionHistoryEntry(): void
    {
        // is there a previous employee position entry?
        // the previous entry should be the one that has no ended_at date
        $previousEntry = EmployeePositionHistory::where('employee_id', $this->employee->id)
            ->where('position_id', $this->previousPositionId)
            ->whereNull('ended_at')
            ->first();

        if ($previousEntry) {
            $previousEntry->ended_at = Carbon::now();
            $previousEntry->save();
        }

        EmployeePositionHistory::create([
            'position_id' => $this->position->id,
            'employee_id' => $this->employee->id,
            'started_at' => Carbon::now(),
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'position_assigned',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
                'position_id' => $this->position->id,
                'position_title' => $this->position->title,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->data['employee_id'],
            'action' => 'position_assigned',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'position_id' => $this->position->id,
                'position_title' => $this->position->title,
            ]),
        ])->onQueue('low');
    }
}
