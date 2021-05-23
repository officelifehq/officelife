<?php

namespace App\Services\Company\Group;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Services\BaseService;
use App\Models\Company\Meeting;

class CreateMeeting extends BaseService
{
    protected array $data;
    protected Group $group;
    protected Meeting $meeting;

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
        ];
    }

    /**
     * Create a meeting.
     *
     * @param array $data
     * @return Meeting
     */
    public function execute(array $data): Meeting
    {
        $this->data = $data;
        $this->validate();
        $this->createMeeting();
        $this->addParticipants();
        $this->log();

        return $this->meeting;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->group = Group::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['group_id']);
    }

    private function createMeeting(): void
    {
        $this->meeting = Meeting::create([
            'group_id' => $this->data['group_id'],
            'happened_at' => Carbon::now()->format('Y-m-d'),
        ]);
    }

    private function addParticipants(): void
    {
        $members = $this->group->employees()
            ->select('id')
            ->get()
            ->pluck('id')
            ->toArray();

        $this->meeting->employees()->syncWithoutDetaching($members);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'meeting_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'group_id' => $this->group->id,
                'group_name' => $this->group->name,
                'meeting_id' => $this->meeting->id,
            ]),
        ])->onQueue('low');
    }
}
