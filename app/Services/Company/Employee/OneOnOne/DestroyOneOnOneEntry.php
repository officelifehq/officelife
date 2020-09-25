<?php

namespace App\Services\Company\Employee\OneOnOne;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\OneOnOneEntry;
use App\Exceptions\NotEnoughPermissionException;

class DestroyOneOnOneEntry extends BaseService
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
        ];
    }

    /**
     * Destroy a one on one entry.
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

        $this->entry = OneOnOneEntry::with('manager')
            ->with('employee')
            ->findOrFail($this->data['one_on_one_entry_id']);

        if ($this->entry->manager_id != $this->data['author_id'] && $this->entry->employee_id != $this->data['author_id']) {
            throw new NotEnoughPermissionException();
        }
    }

    private function destroy(): void
    {
        $this->entry->delete();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'one_on_one_entry_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'happened_at' => $this->entry->happened_at->format('Y-m-d'),
                'employee_id' => $this->entry->employee->id,
                'employee_name' => $this->entry->employee->name,
                'manager_id' => $this->entry->manager->id,
                'manager_name' => $this->entry->manager->name,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->entry->employee->id,
            'action' => 'one_on_one_entry_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'happened_at' => $this->entry->happened_at->format('Y-m-d'),
                'employee_id' => $this->entry->manager->id,
                'employee_name' => $this->entry->manager->name,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->entry->manager->id,
            'action' => 'one_on_one_entry_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'happened_at' => $this->entry->happened_at->format('Y-m-d'),
                'employee_id' => $this->entry->employee->id,
                'employee_name' => $this->entry->employee->name,
            ]),
        ])->onQueue('low');
    }
}
