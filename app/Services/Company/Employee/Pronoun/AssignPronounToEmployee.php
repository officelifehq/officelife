<?php

namespace App\Services\Company\Employee\Pronoun;

use Carbon\Carbon;
use App\Models\User\Pronoun;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;

class AssignPronounToEmployee extends BaseService
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
            'pronoun_id' => 'required|integer|exists:pronouns,id',
        ];
    }

    /**
     * Set an employee's gender pronoun.
     *
     * @param array $data
     *
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfEmployee($data['employee_id'])
            ->canExecuteService();

        $employee = $this->validateEmployeeBelongsToCompany($data);

        $pronoun = Pronoun::findOrFail($data['pronoun_id']);

        $employee->pronoun_id = $data['pronoun_id'];
        $employee->save();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'pronoun_assigned_to_employee',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'pronoun_id' => $pronoun->id,
                'pronoun_label' => $pronoun->label,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $data['employee_id'],
            'action' => 'pronoun_assigned',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'pronoun_id' => $pronoun->id,
                'pronoun_label' => $pronoun->label,
            ]),
        ])->onQueue('low');

        return $employee;
    }
}
