<?php

namespace App\Services\Company\Employee\Skill;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Skill;
use App\Services\BaseService;

class DestroySkill extends BaseService
{
    private ?Skill $skill;

    private array $data;

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
            'skill_id' => 'required|integer|exists:skills,id',
        ];
    }

    /**
     * Destroy a skill.
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
            ->canExecuteService();

        $this->skill = Skill::where('company_id', $data['company_id'])
            ->findOrFail($data['skill_id']);

        $this->destroy();

        $this->log();
    }

    /**
     * Actually destroy the skill.
     */
    private function destroy(): void
    {
        $this->skill->delete();
    }

    /**
     * Add audit logs.
     */
    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'skill_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'skill_name' => $this->skill->name,
            ]),
        ])->onQueue('low');
    }
}
