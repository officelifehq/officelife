<?php

namespace App\Services\Company\Group;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Services\BaseService;

class CreateGroup extends BaseService
{
    protected array $data;
    protected Group $group;

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
            'name' => 'required|string|max:255',
        ];
    }

    /**
     * Create a group.
     *
     * @param array $data
     * @return Group
     */
    public function execute(array $data): Group
    {
        $this->data = $data;
        $this->validate();
        $this->createGroup();
        $this->log();

        return $this->group;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();
    }

    private function createGroup(): void
    {
        $this->group = Group::create([
            'company_id' => $this->data['company_id'],
            'name' => $this->data['name'],
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'group_created',
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
