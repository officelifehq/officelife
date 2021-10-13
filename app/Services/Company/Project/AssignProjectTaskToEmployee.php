<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectTask;
use App\Models\Company\ProjectMemberActivity;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AssignProjectTaskToEmployee extends BaseService
{
    protected array $data;
    protected ProjectTask $projectTask;
    protected Employee $assignee;
    protected Project $project;

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
            'project_id' => 'required|integer|exists:projects,id',
            'project_task_id' => 'required|integer|exists:project_tasks,id',
            'assignee_id' => 'required|integer|exists:employees,id',
        ];
    }

    /**
     * Assign the project task to an employee.
     *
     * @param array $data
     * @return ProjectTask
     */
    public function execute(array $data): ProjectTask
    {
        $this->data = $data;
        $this->validate();
        $this->assign();
        $this->logActivity();
        $this->log();

        return $this->projectTask;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->project = Project::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['project_id']);

        $this->projectTask = ProjectTask::where('project_id', $this->data['project_id'])
            ->findOrFail($this->data['project_task_id']);

        $this->assignee = Employee::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['assignee_id']);

        if (! $this->assignee->isInProject($this->project->id)) {
            throw new ModelNotFoundException();
        }
    }

    private function assign(): void
    {
        $this->projectTask->assignee_id = $this->assignee->id;
        $this->projectTask->save();
    }

    private function logActivity(): void
    {
        ProjectMemberActivity::create([
            'project_id' => $this->project->id,
            'employee_id' => $this->author->id,
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'project_task_assigned_to_assignee',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'project_task_id' => $this->projectTask->id,
                'project_task_title' => $this->projectTask->title,
                'assignee_id' => $this->assignee->id,
                'assignee_name' => $this->assignee->name,
            ]),
        ])->onQueue('low');
    }
}
