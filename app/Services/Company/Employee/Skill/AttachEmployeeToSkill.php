<?php

namespace App\Services\Company\Employee\Skill;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Skill;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;

class AttachEmployeeToSkill extends BaseService
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
            'name' => 'required|string|max:255',
        ];
    }

    /**
     * Attach a skill to an employee.
     *
     * @param array $data
     *
     * @return Skill|null
     */
    public function execute(array $data): ?Skill
    {
        $this->data = $data;
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfEmployee($data['employee_id'])
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($data);

        $this->lookupExistingSkill();

        $this->attachEmployee();

        $this->logSkillAssociatedWithEmployee();

        return $this->skill;
    }

    /**
     * Check if a skill already exists in the company.
     */
    private function lookupExistingSkill(): void
    {
        $name = Str::of($this->data['name'])->ascii()->lower();

        $this->skill = Skill::where('company_id', $this->data['company_id'])
            ->where('name', $name)
            ->first();

        if (! $this->skill) {
            $this->createSkill();
        }

        $this->logCreated();
    }

    /**
     * Actually create the skill.
     */
    private function createSkill(): void
    {
        $name = Str::of($this->data['name'])->ascii()->lower();

        $this->skill = Skill::create([
            'company_id' => $this->data['company_id'],
            'name' => $name,
        ]);
        $this->skill->refresh();
    }

    /**
     * Attach the employee to the skill.
     */
    private function attachEmployee(): void
    {
        $this->skill->employees()->syncWithoutDetaching([
            $this->data['employee_id'],
        ]);
    }

    /**
     * Add log about the skill being created in the company.
     */
    private function logCreated(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'skill_created',
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
    }

    /**
     * Add logs about the skill being associated with an employee.
     */
    private function logSkillAssociatedWithEmployee(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'skill_associated_with_employee',
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
            'action' => 'skill_associated_with_employee',
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
