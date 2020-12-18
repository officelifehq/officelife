<?php

namespace App\Services\Company\Employee\Timesheet;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use App\Models\Company\ProjectTask;
use App\Models\Company\TimeTrackingEntry;
use Carbon\Exceptions\InvalidDateException;
use App\Exceptions\DurationExceedingMaximalDurationException;

class CreateTimeTrackingEntry extends BaseService
{
    private array $data;

    private Employee $employee;

    private Timesheet $timesheet;

    private ?TimeTrackingEntry $timeTrackingEntry;

    private Project $project;

    private ProjectTask $projectTask;

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
            'project_id' => 'required|integer|exists:projects,id',
            'project_task_id' => 'required|integer|exists:project_tasks,id',
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
        $this->createCarbonDate();
        $this->makeSureTimeEnteredIsCorrect();
        $this->checkPastTimeTrackingEntryExistence();
        $this->createOrUpdateTimeTrackingEntry();
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

        if (! is_null($this->data['project_id']) && ! is_null($this->data['project_task_id'])) {
            $this->project = Project::where('company_id', $this->data['company_id'])
                ->findOrFail($this->data['project_id']);

            $this->projectTask = ProjectTask::where('project_id', $this->data['project_id'])
                ->findOrFail($this->data['project_task_id']);
        }
    }

    private function makeSureTimeEnteredIsCorrect(): void
    {
        // how much is the duration of all the time entries for the timesheet?
        $entries = $this->timesheet->timeTrackingEntries;
        $totalDurationForTheWeek = $entries->sum('duration');
        $totalDurationForTheDay = $entries->filter(function ($entry) {
            return $entry->happened_at->format('Y-m-d') == $this->date->format('Y-m-d');
        })->sum('duration');

        // this duration shouldn't be longer than 24 hours X 7 days = 10 080 min
        $newTotalDuration = $totalDurationForTheWeek + $this->data['duration'];

        if ((int) $newTotalDuration > 10080) {
            throw new DurationExceedingMaximalDurationException();
        }

        // duration for the day shouldn't exceed 1440 minutes (24*60)
        $newTotalDuration = $totalDurationForTheDay + $this->data['duration'];
        if ((int) $newTotalDuration > 1440) {
            throw new DurationExceedingMaximalDurationException();
        }
    }

    private function getTimesheet(): void
    {
        $this->timesheet = (new CreateOrGetTimesheet)->execute([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->data['author_id'],
            'employee_id' => $this->data['employee_id'],
            'date' => $this->data['date'],
        ]);
    }

    private function createCarbonDate(): void
    {
        try {
            $this->date = Carbon::createFromFormat('Y-m-d', $this->data['date']);
            $this->date->hour(00);
            $this->date->minute(00);
            $this->date->second(00);
        } catch (InvalidDateException $e) {
            throw new \Exception(trans('app.error_invalid_date'));
        }
    }

    private function checkPastTimeTrackingEntryExistence(): void
    {
        // we need to check if there is an entry already for this date
        // and the task in this project
        $this->timeTrackingEntry = TimeTrackingEntry::where('happened_at', $this->date)
            ->where('timesheet_id', $this->timesheet->id)
            ->where('employee_id', $this->employee->id)
            ->where('project_task_id', is_null($this->data['project_task_id']) ? null : $this->projectTask->id)
            ->where('project_id', is_null($this->data['project_id']) ? null : $this->project->id)
            ->first();
    }

    private function createOrUpdateTimeTrackingEntry(): void
    {
        if ($this->timeTrackingEntry) {
            $this->updateEntry();
        } else {
            $this->createEntry();
        }
    }

    private function createEntry(): void
    {
        $this->timeTrackingEntry = TimeTrackingEntry::create([
            'timesheet_id' => $this->timesheet->id,
            'employee_id' => $this->employee->id,
            'project_id' => is_null($this->data['project_id']) ? null : $this->project->id,
            'project_task_id' => is_null($this->data['project_task_id']) ? null : $this->projectTask->id,
            'duration' => $this->data['duration'],
            'happened_at' => $this->date,
            'description' => $this->valueOrNull($this->data, 'description'),
        ]);
    }

    private function updateEntry(): void
    {
        $this->timeTrackingEntry->duration = $this->data['duration'];
        $this->timeTrackingEntry->save();
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
