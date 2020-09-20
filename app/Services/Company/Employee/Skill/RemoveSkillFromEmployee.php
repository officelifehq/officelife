<?php

namespace App\Services\Company\Employee\Skill;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Skill;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;

class RemoveSkillFromEmployee extends BaseService
{
    private array $data;

    private Employee $employee;

    private ?Skill $skill;

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
            'skill_id' => 'required|integer|exists:skills,id',
        ];
    }

    /**
     * Detach a skill from an employee.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfEmployee($data['employee_id'])
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($data);

        $this->skill = Skill::where('company_id', $data['company_id'])
            ->findOrFail($data['skill_id']);

        $this->detachEmployee();

        $this->destroySkillIfNoOneUsesIt();

        $this->log();
    }

    /**
     * Attach the employee to the skill.
     */
    private function detachEmployee(): void
    {
        $this->skill->employees()->detach($this->data['employee_id']);
    }

    /**
     * Destroy the skill if no other employee has the skill associated with them.
     * This is because it's useless to keep a skill that no one uses.
     */
    private function destroySkillIfNoOneUsesIt(): void
    {
        $employees = $this->skill->employees;

        if (count($employees) == 0) {
            $this->skill->delete();
        }
    }

    /**
     * Add logs.
     */
    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'skill_removed_from_an_employee',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'skill_id' => $this->skill->id,
                'skill_name' => $this->skill->name,
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'skill_removed_from_an_employee',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'skill_id' => $this->skill->id,
                'skill_name' => $this->skill->name,
            ]),
        ])->onQueue('low');
    }
}
