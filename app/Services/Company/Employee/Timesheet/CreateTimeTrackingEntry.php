<?php

namespace App\Services\Company\Employee\Timesheet;

use Carbon\Carbon;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use App\Models\Company\TimeTrackingEntry;
use Carbon\Exceptions\InvalidDateException;
use App\Exceptions\NotEnoughPermissionException;

class CreateTimeTrackingEntry extends BaseService
{
    private array $data;

    private Employee $employee;

    private Timesheet $timesheet;

    private TimeTrackingEntry $timeTrackingEntry;

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
        $this->createTimeTrackingEntry();
        $this->log();

        return $this->t;
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

            if ($this->project->company_id != $this->company_id) {
                throw new NotEnoughPermissionException();
            }
        }
    }

    private function createTimeTrackingEntry(): Timesheet
    {
        try {
            $date = Carbon::createFromFormat('Y-m-d', $this->data['date']);
        } catch (InvalidDateException $e) {
            throw new \Exception(trans('app.error_invalid_date'));
        }

        $this->timesheet = (new CreateTimesheet)->execute([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->data['author_id'],
            'employee_id' => $this->data['employee_id'],
            'date' => $this->data['date'],
        ]);

        // no existing timesheet - we need to create a timesheet
        return TimeTrackingEntry::create([
            'timesheet_id' => $this->timesheet->id,
            'employee_id' => $this->employee->id,
            'project_id' => is_null($this->data['project_id']) ? null : $this->project->id,
            'duration' => $this->data['duration'],
            'happened_at' => $date,
            'description' => $this->valueOrNull($this->data, 'description'),
        ]);
    }
}
