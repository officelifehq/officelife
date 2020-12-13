<?php

namespace App\Services\Company\Employee\Skill;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Skill;
use App\Services\BaseService;
use App\Exceptions\SkillNameNotUniqueException;

class UpdateSkill extends BaseService
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
            'name' => 'required|string|max:255',
        ];
    }

    /**
     * Update a skill.
     *
     * @param array $data
     *
     * @return Skill
     */
    public function execute(array $data): Skill
    {
        $this->data = $data;
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->skill = Skill::where('company_id', $data['company_id'])
            ->findOrFail($data['skill_id']);

        $this->verifySkillNameUniqueness();

        $this->updateName();

        return $this->skill->refresh();
    }

    /**
     * Make sure the skill's name is unique in the company.
     */
    private function verifySkillNameUniqueness(): void
    {
        $name = Str::of($this->data['name'])->ascii()->lower();

        $uniqueSkill = Skill::where('company_id', $this->data['company_id'])
            ->where('name', $name)
            ->first();

        if ($uniqueSkill) {
            throw new SkillNameNotUniqueException(trans('app.error_skill_name_not_unique'));
        }
    }

    /**
     * Add audit logs.
     *
     * @param string $oldName
     */
    private function log(string $oldName, string $newName): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'skill_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'skill_id' => $this->data['skill_id'],
                'skill_old_name' => $oldName,
                'skill_new_name' => $newName,
            ]),
        ])->onQueue('low');
    }

    /**
     * Actually update the skill.
     */
    private function updateName(): void
    {
        $oldName = $this->skill->name;
        $name = Str::of($this->data['name'])->ascii()->lower();

        $this->skill->update([
            'name' => $name,
        ]);

        $this->log($oldName, $name);
    }
}
