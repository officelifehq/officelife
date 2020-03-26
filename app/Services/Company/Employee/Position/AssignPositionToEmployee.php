<?php

namespace App\Services\Company\Employee\Position;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\Position;

class AssignPositionToEmployee extends BaseService
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
            'position_id' => 'required|integer|exists:positions,id',
            'is_dummy' => 'nullable|boolean',
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
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $employee = $this->validateEmployeeBelongsToCompany($data);

        $position = Position::where('company_id', $data['company_id'])
            ->findOrFail($data['position_id']);

        $employee->position_id = $position->id;
        $employee->save();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'position_assigned',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'position_id' => $position->id,
                'position_title' => $position->title,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $data['employee_id'],
            'action' => 'position_assigned',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'position_id' => $position->id,
                'position_title' => $position->title,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        return $employee;
    }
}
