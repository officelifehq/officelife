<?php

namespace App\Services\Company\Employee\Timesheet;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use App\Models\Company\TimeTrackingEntry;
use Carbon\Exceptions\InvalidDateException;
use App\Exceptions\DurationExceedingMaximalDurationException;

class CreateTimeTrackingEntry extends BaseService
{
    private array $data;

    private Employee $employee;

    private Timesheet $timesheet;

    private TimeTrackingEntry $timeTrackingEntry;

    private Carbon $date;

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
            'employee_id' => 'required|integer|exists:employees,id',
            'project_id' => 'nullable|integer|exists:projects,id',
            'duration' => 'required|integer',
            'date' => 'required|date_format:Y-m-d',
            'description' => 'nullable|string|max:65535',
        ];
    }

    /**
     * Create a time tracking entry in a given timesheet.
     * When we create a time tracking entry, we need to make sure that the
     * overall timesheet for the day is not larger than 24 hours - in this case,
     * we need to inform the user that the duration is too big.
     *
     * @param array $data
     * @return TimeTrackingEntry
     */
    public function execute(array $data): TimeTrackingEntry
    {
        $this->data = $data;
        $this->validate();
        $this->getTimesheet();
        $this->createTimeTrackingEntry();
        $this->log();

        return $this->timeTrackingEntry;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfEmployee($this->data['employee_id'])
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($this->data);

        if (! is_null($this->data['project_id'])) {
            $this->project = Project::where('company_id', $this->data['company_id'])
                ->findOrFail($this->data['project_id']);
        }
    }

    private function getTimesheet(): void
    {
        $this->timesheet = (new CreateTimesheet)->execute([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->data['author_id'],
            'employee_id' => $this->data['employee_id'],
            'date' => $this->data['date'],
        ]);
    }

    private function createTimeTrackingEntry(): void
    {
        // how much is the duration of all the time entries for the timesheet?
        $entries = $this->timesheet->timeTrackingEntries;

        $totalDuration = $entries->sum('duration');

        // duration is in minutes in the DB, so 24 hours is 1440 minutes
        if (($totalDuration + $this->data['duration']) > 1440) {
            throw new DurationExceedingMaximalDurationException();
        }

        try {
            $this->date = Carbon::createFromFormat('Y-m-d', $this->data['date']);
            $this->date->hour(00);
            $this->date->minute(00);
            $this->date->second(00);
        } catch (InvalidDateException $e) {
            throw new \Exception(trans('app.error_invalid_date'));
        }

        $this->timeTrackingEntry = TimeTrackingEntry::create([
            'timesheet_id' => $this->timesheet->id,
            'employee_id' => $this->employee->id,
            'project_id' => is_null($this->data['project_id']) ? null : $this->project->id,
            'duration' => $this->data['duration'],
            'happened_at' => $this->date,
            'description' => $this->valueOrNull($this->data, 'description'),
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'time_tracking_entry_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
                'week_number' => $this->date->weekOfYear,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'employee_id' => $this->data['employee_id'],
            'action' => 'time_tracking_entry_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'week_number' => $this->date->weekOfYear,
            ]),
        ])->onQueue('low');
    }
}
