<?php

namespace App\Services\Company\Employee\OneOnOne;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\OneOnOneEntry;
use App\Models\Company\OneOnOneTalkingPoint;
use App\Exceptions\NotEnoughPermissionException;

class AddOneOnOneTalkingPoint extends BaseService
{
    protected array $data;

    protected OneOnOneEntry $entry;

    protected OneOnOneTalkingPoint $talkingPoint;

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
            'description' => 'required|string|max:255',
        ];
    }

    /**
     * Create a one on one talking point for a one on one meeting.
     *
     * @param array $data
     * @return OneOnOneTalkingPoint
     */
    public function execute(array $data): OneOnOneTalkingPoint
    {
        $this->data = $data;
        $this->validate();
        $this->create();
        $this->log();

        return $this->talkingPoint;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->entry = OneOnOneEntry::findOrFail($this->data['one_on_one_entry_id']);

        if ($this->author->id != $this->entry->manager->id && $this->author->id != $this->entry->employee->id) {
            throw new NotEnoughPermissionException(trans('app.error_not_enough_permission'));
        }
    }

    private function create(): void
    {
        $this->talkingPoint = OneOnOneTalkingPoint::create([
            'one_on_one_entry_id' => $this->data['one_on_one_entry_id'],
            'description' => $this->data['description'],
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'one_on_one_talking_point_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'one_on_one_entry_id' => $this->entry->id,
                'one_on_one_talking_point_id' => $this->talkingPoint->id,
                'happened_at' => $this->entry->happened_at->format('Y-m-d'),
            ]),
            'is_dummy' => false,
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->entry->employee->id,
            'action' => 'one_on_one_talking_point_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'one_on_one_entry_id' => $this->entry->id,
                'one_on_one_talking_point_id' => $this->talkingPoint->id,
                'happened_at' => $this->entry->happened_at->format('Y-m-d'),
            ]),
            'is_dummy' => false,
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->entry->manager->id,
            'action' => 'one_on_one_talking_point_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'one_on_one_entry_id' => $this->entry->id,
                'one_on_one_talking_point_id' => $this->talkingPoint->id,
                'happened_at' => $this->entry->happened_at->format('Y-m-d'),
            ]),
            'is_dummy' => false,
        ])->onQueue('low');
    }
}
