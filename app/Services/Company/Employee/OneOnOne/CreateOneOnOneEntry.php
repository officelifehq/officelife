<?php

namespace App\Services\Company\Employee\OneOnOne;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Exceptions\SameIdsException;
use App\Models\Company\OneOnOneEntry;
use App\Exceptions\NotTheManagerException;
use App\Models\Company\OneOnOneTalkingPoint;
use App\Exceptions\NotEnoughPermissionException;

class CreateOneOnOneEntry extends BaseService
{
    protected array $data;

    protected Employee $employee;

    protected Employee $manager;

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
            'manager_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'date' => 'required|date_format:Y-m-d',
        ];
    }

    /**
     * Create a one on one entry.
     *
     * @param array $data
     * @return OneOnOneEntry
     */
    public function execute(array $data): OneOnOneEntry
    {
        $this->data = $data;
        $this->validate();
        $this->create();
        $this->carryOverPreviousUncompletedTasks();
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

        if ($this->data['manager_id'] == $this->data['employee_id']) {
            throw new SameIdsException();
        }

        $this->employee = Employee::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['employee_id']);

        $this->manager = Employee::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['manager_id']);

        if ($this->author->id != $this->manager->id && $this->author->id != $this->employee->id && $this->author->permission_level > 200) {
            throw new NotEnoughPermissionException(trans('app.error_not_enough_permission'));
        }

        if (! $this->manager->isManagerOf($this->employee->id)) {
            throw new NotTheManagerException();
        }
    }

    private function create(): void
    {
        $this->entry = OneOnOneEntry::create([
            'manager_id' => $this->data['manager_id'],
            'employee_id' => $this->data['employee_id'],
            'happened_at' => $this->data['date'],
        ]);
    }

    /**
     * Migrate previous uncompleted tasks, if there was a previous entry, to
     * this new entry.
     */
    private function carryOverPreviousUncompletedTasks(): void
    {
        // get previous entry
        $entries = OneOnOneEntry::where('manager_id', $this->data['manager_id'])
            ->where('employee_id', $this->data['employee_id'])
            ->with('actionItems')
            ->orderBy('happened_at', 'desc')
            ->take(2)
            ->get();

        $currentEntryId = $this->entry->id;

        // filter the first entry which should be the current entry
        $beforeLastEntry = $entries->filter(function ($entry) use ($currentEntryId) {
            return $entry->id != $currentEntryId;
        })->first();

        if (! $beforeLastEntry) {
            return;
        }

        $items = $beforeLastEntry->actionItems()->where('checked', false)
            ->get();

        // copy past items
        if ($items->count() != 0) {
            foreach ($items as $item) {
                OneOnOneTalkingPoint::create([
                    'one_on_one_entry_id' => $this->entry->id,
                    'description' => $item->description,
                    'checked' => $item->checked,
                ]);
            }
        }
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'one_on_one_entry_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'one_on_one_entry_id' => $this->entry->id,
                'employee_id' => $this->data['employee_id'],
                'employee_name' => $this->employee->name,
                'manager_id' => $this->data['manager_id'],
                'manager_name' => $this->manager->name,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'one_on_one_entry_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'one_on_one_entry_id' => $this->entry->id,
                'employee_id' => $this->data['manager_id'],
                'employee_name' => $this->manager->name,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->manager->id,
            'action' => 'one_on_one_entry_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'one_on_one_entry_id' => $this->entry->id,
                'employee_id' => $this->data['employee_id'],
                'employee_name' => $this->employee->name,
            ]),
        ])->onQueue('low');
    }
}
