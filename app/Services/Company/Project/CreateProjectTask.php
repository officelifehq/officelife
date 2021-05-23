<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\ProjectTask;
use App\Models\Company\ProjectTaskList;
use App\Models\Company\ProjectMemberActivity;

class CreateProjectTask extends BaseService
{
    protected array $data;

    protected ProjectTask $projectTask;

    protected ProjectTaskList $projectTaskList;

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
            'project_task_list_id' => 'nullable|integer|exists:project_task_lists,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
        ];
    }

    /**
     * Create a project task.
     *
     * @param array $data
     * @return ProjectTask
     */
    public function execute(array $data): ProjectTask
    {
        $this->data = $data;
        $this->validate();
        $this->createTask();
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

        if (! is_null($this->valueOrNull($this->data, 'project_task_list_id'))) {
            $this->projectTaskList = ProjectTaskList::where('project_id', $this->data['project_id'])
                ->findOrFail($this->data['project_task_list_id']);
        }
    }

    private function createTask(): void
    {
        $this->projectTask = ProjectTask::create([
            'project_id' => $this->data['project_id'],
            'project_task_list_id' => $this->valueOrNull($this->data, 'project_task_list_id'),
            'author_id' => $this->data['author_id'],
            'assignee_id' => $this->valueOrNull($this->data, 'assignee_id'),
            'title' => $this->data['title'],
            'description' => $this->data['description'],
        ]);
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
            'action' => 'project_task_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_task_id' => $this->projectTask->id,
                'project_task_title' => $this->projectTask->title,
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
            ]),
        ])->onQueue('low');
    }
}
