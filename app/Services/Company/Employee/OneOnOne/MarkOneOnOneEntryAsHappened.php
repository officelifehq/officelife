<?php

namespace App\Services\Company\Employee\OneOnOne;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\OneOnOneEntry;
use App\Exceptions\NotEnoughPermissionException;

class MarkOneOnOneEntryAsHappened extends BaseService
{
    protected array $data;

    protected OneOnOneEntry $entry;

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
            'date' => 'nullable|date_format:Y-m-d',
        ];
    }

    /**
     * Mark a one on one entry as happened, and create a new entry for next time.
     *
     * @param array $data
     * @return OneOnOneEntry
     */
    public function execute(array $data): OneOnOneEntry
    {
        $this->data = $data;
        $this->validate();
        $this->mark();
        $this->createNewEntry();
        $this->log();

        return $this->entry;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->entry = OneOnOneEntry::findOrFail($this->data['one_on_one_entry_id']);

        if ($this->author->id != $this->entry->manager->id && $this->author->id != $this->entry->employee->id && $this->author->permission_level > 200) {
            throw new NotEnoughPermissionException(trans('app.error_not_enough_permission'));
        }
    }

    private function mark(): void
    {
        $this->entry->happened = true;
        $this->entry->save();
    }

    private function createNewEntry(): void
    {
        $this->entry = (new CreateOneOnOneEntry)->execute([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->author->id,
            'manager_id' => $this->entry->manager->id,
            'employee_id' => $this->entry->employee->id,
            'date' => $this->valueOrFalse($this->data, 'date') ? $this->data['date'] : Carbon::now()->format('Y-m-d'),
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'one_on_one_note_marked_happened',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'one_on_one_entry_id' => $this->entry->id,
                'happened_at' => $this->entry->happened_at->format('Y-m-d'),
                'employee_id' => $this->entry->employee->id,
                'employee_name' => $this->entry->employee->name,
                'manager_id' => $this->entry->manager->id,
                'manager_name' => $this->entry->manager->name,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->entry->employee->id,
            'action' => 'one_on_one_note_marked_happened',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'one_on_one_entry_id' => $this->entry->id,
                'happened_at' => $this->entry->happened_at->format('Y-m-d'),
                'employee_id' => $this->entry->manager->id,
                'employee_name' => $this->entry->manager->name,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->entry->manager->id,
            'action' => 'one_on_one_note_marked_happened',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'one_on_one_entry_id' => $this->entry->id,
                'happened_at' => $this->entry->happened_at->format('Y-m-d'),
                'employee_id' => $this->entry->employee->id,
                'employee_name' => $this->entry->employee->name,
            ]),
        ])->onQueue('low');
    }
}
