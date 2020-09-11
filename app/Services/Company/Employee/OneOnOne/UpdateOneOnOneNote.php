<?php

namespace App\Services\Company\Employee\OneOnOne;

use App\Services\BaseService;
use App\Models\Company\OneOnOneNote;
use App\Models\Company\OneOnOneEntry;
use App\Exceptions\NotEnoughPermissionException;

class UpdateOneOnOneNote extends BaseService
{
    protected array $data;

    protected OneOnOneEntry $entry;

    protected OneOnOneNote $note;

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
            'one_on_one_note_id' => 'required|integer|exists:one_on_one_notes,id',
            'note' => 'required|string|max:65535',
        ];
    }

    /**
     * Update a one on one talking point for a one on one meeting.
     *
     * @param array $data
     * @return OneOnOneNote
     */
    public function execute(array $data): OneOnOneNote
    {
        $this->data = $data;
        $this->validate();
        $this->update();

        return $this->note;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->entry = OneOnOneEntry::with('manager')
            ->with('employee')
            ->findOrFail($this->data['one_on_one_entry_id']);

        $this->note = OneOnOneNote::where('one_on_one_entry_id', $this->entry->id)
            ->findOrFail($this->data['one_on_one_note_id']);

        if ($this->author->id != $this->entry->manager->id && $this->author->id != $this->entry->employee->id) {
            throw new NotEnoughPermissionException(trans('app.error_not_enough_permission'));
        }
    }

    private function update(): void
    {
        $this->note->note = $this->data['note'];
        $this->note->save();
    }
}
