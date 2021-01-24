<?php

namespace App\Services\Company\Employee\Timesheet;

use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use App\Models\Company\ProjectTask;
use App\Models\Company\TimeTrackingEntry;

class DestroyTimesheetRow extends BaseService
{
    private array $data;

    private Employee $employee;

    private Timesheet $timesheet;

    private Project $project;

    private ProjectTask $projectTask;

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
            'timesheet_id' => 'required|integer|exists:timesheets,id',
            'project_id' => 'required|integer|exists:projects,id',
            'project_task_id' => 'required|integer|exists:project_tasks,id',
        ];
    }

    /**
     * Destroy all time tracking entries at a given date for a given project
     * task in a given timesheet.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->destroyEntryForProject();
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

        $this->timesheet = Timesheet::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['timesheet_id']);

        $this->project = Project::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['project_id']);

        $this->projectTask = ProjectTask::where('project_id', $this->data['project_id'])
            ->findOrFail($this->data['project_task_id']);
    }

    private function destroyEntryForProject(): void
    {
        TimeTrackingEntry::where('timesheet_id', $this->timesheet->id)
            ->where('employee_id', $this->employee->id)
            ->where('project_id', $this->project->id)
            ->where('project_task_id', $this->projectTask->id)
            ->delete();
    }
}
