<?php

namespace App\Services\Company\Group;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Services\BaseService;
use App\Models\Company\Meeting;
use App\Models\Company\AgendaItem;

class DestroyAgendaItem extends BaseService
{
    protected array $data;
    protected Group $group;
    protected Meeting $meeting;
    protected AgendaItem $agendaItem;

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
        ];
    }

    /**
     * Delete an agenda item.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->destroy();
        $this->reorderPosition();
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
    }

    private function destroy(): void
    {
        $this->agendaItem->delete();
    }

    private function reorderPosition(): void
    {
        $formerPosition = $this->agendaItem->position;

        $agendaItems = AgendaItem::where('meeting_id', $this->meeting->id)
            ->where('position', '>', $formerPosition)
            ->get();

        foreach ($agendaItems as $item) {
            $item->position = $item->position - 1;
            $item->save();
        }
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'agenda_item_destroyed',
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
