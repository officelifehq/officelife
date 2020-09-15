<?php

namespace App\Services\Company\Employee\OneOnOne;

use App\Services\BaseService;
use App\Models\Company\OneOnOneActionItem;
use App\Exceptions\NotEnoughPermissionException;

class ToggleOneOnOneActionItem extends BaseService
{
    protected array $data;

    protected OneOnOneActionItem $actionItem;

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
            'one_on_one_entry_id' => 'required|integer|exists:one_on_one_entries,id',
            'one_on_one_action_item_id' => 'required|integer|exists:one_on_one_action_items,id',
        ];
    }

    /**
     * Toggle a one on one action item for a one on one meeting.
     *
     * @param array $data
     * @return OneOnOneActionItem
     */
    public function execute(array $data): OneOnOneActionItem
    {
        $this->data = $data;
        $this->validate();
        $this->toggle();

        return $this->actionItem;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->actionItem = OneOnOneActionItem::where('one_on_one_entry_id', $this->data['one_on_one_entry_id'])
            ->findOrFail($this->data['one_on_one_action_item_id']);

        if ($this->author->id != $this->actionItem->entry->manager->id &&
            $this->author->id != $this->actionItem->entry->employee->id &&
            $this->author->permission_level > 200) {
            throw new NotEnoughPermissionException(trans('app.error_not_enough_permission'));
        }
    }

    private function toggle(): void
    {
        $this->actionItem->checked = ! $this->actionItem->checked;
        $this->actionItem->save();
    }
}
