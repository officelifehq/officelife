<?php

namespace App\Services\Company\Group;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Meeting;
use App\Models\Company\AgendaItem;
use App\Models\Company\MeetingDecision;

class CreateMeetingDecision extends BaseService
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
            'group_id' => 'required|integer|exists:groups,id',
            'meeting_id' => 'required|integer|exists:meetings,id',
            'agenda_item_id' => 'required|integer|exists:agenda_items,id',
            'description' => 'required|string|max:65535',
        ];
    }

    /**
     * Create a decision about an agenda item in a meeting.
     *
     * @param array $data
     * @return MeetingDecision
     */
    public function execute(array $data): MeetingDecision
    {
        $this->data = $data;
        $this->validate();
        $this->createDecision();
        $this->log();

        return $this->meetingDecision;
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
    }

    private function createDecision(): void
    {
        $this->meetingDecision = MeetingDecision::create([
            'agenda_item_id' => $this->data['agenda_item_id'],
            'description' => $this->data['description'],
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'meeting_decision_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'group_id' => $this->group->id,
                'group_name' => $this->group->name,
                'meeting_id' => $this->meeting->id,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->author->id,
            'action' => 'meeting_decision_created',
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
