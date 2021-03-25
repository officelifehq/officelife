<?php

namespace App\Services\Company\Group;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Services\BaseService;
use App\Models\Company\Meeting;
use App\Models\Company\AgendaItem;
use App\Models\Company\MeetingDecision;

class DestroyMeetingDecision extends BaseService
{
    protected array $data;
    protected Group $group;
    protected Meeting $meeting;
    protected AgendaItem $agendaItem;
    protected MeetingDecision $meetingDecision;

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
            'group_id' => 'nullable|integer|exists:groups,id',
            'meeting_id' => 'nullable|integer|exists:meetings,id',
            'agenda_item_id' => 'nullable|integer|exists:agenda_items,id',
            'meeting_decision_id' => 'nullable|integer|exists:meeting_decisions,id',
        ];
    }

    /**
     * Delete a meeting decision.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->destroy();
        $this->log();
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

        $this->meeting = Meeting::where('group_id', $this->data['group_id'])
            ->findOrFail($this->data['meeting_id']);

        $this->agendaItem = AgendaItem::where('meeting_id', $this->data['meeting_id'])
            ->findOrFail($this->data['agenda_item_id']);

        $this->meetingDecision = MeetingDecision::where('agenda_item_id', $this->data['agenda_item_id'])
            ->findOrFail($this->data['meeting_decision_id']);
    }

    private function destroy(): void
    {
        $this->meetingDecision->delete();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'meeting_decision_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'group_name' => $this->group->name,
            ]),
        ])->onQueue('low');
    }
}
