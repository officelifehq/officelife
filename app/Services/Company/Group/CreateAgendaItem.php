<?php

namespace App\Services\Company\Group;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Meeting;
use App\Models\Company\Employee;
use App\Models\Company\AgendaItem;

class CreateAgendaItem extends BaseService
{
    protected array $data;
    protected Group $group;
    protected Meeting $meeting;
    protected AgendaItem $agendaItem;
    protected Employee $presenter;

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
            'summary' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
            'presented_by_id' => 'nullable|integer|exists:employees,id',
        ];
    }

    /**
     * Create an agenda item in a meeting.
     *
     * @param array $data
     * @return AgendaItem
     */
    public function execute(array $data): AgendaItem
    {
        $this->data = $data;
        $this->validate();
        $this->createAgendaItem();
        $this->log();

        return $this->agendaItem;
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

        if ($this->data['presented_by_id']) {
            $this->presenter = Employee::where('company_id', $this->data['company_id'])
                ->findOrFail($this->data['presented_by_id']);
        }
    }

    private function createAgendaItem(): void
    {
        // get the position of the agenda item
        $max = AgendaItem::where('meeting_id', $this->meeting->id)
            ->max('position');

        $this->agendaItem = AgendaItem::create([
            'meeting_id' => $this->data['meeting_id'],
            'position' => $max + 1,
            'summary' => $this->data['summary'],
            'description' => $this->data['description'],
            'presented_by_id' => $this->data['presented_by_id'] ? $this->data['presented_by_id'] : null,
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'agenda_item_created',
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
            'action' => 'agenda_item_created',
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
