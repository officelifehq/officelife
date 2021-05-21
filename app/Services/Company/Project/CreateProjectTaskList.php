<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\ProjectTaskList;
use App\Models\Company\ProjectMemberActivity;

class CreateProjectTaskList extends BaseService
{
    protected array $data;

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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
        ];
    }

    /**
     * Create a project task list.
     *
     * @param array $data
     * @return ProjectTaskList
     */
    public function execute(array $data): ProjectTaskList
    {
        $this->data = $data;
        $this->validate();
        $this->createTaskList();
        $this->logActivity();
        $this->log();

        return $this->projectTaskList;
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
    }

    private function createTaskList(): void
    {
        $this->projectTaskList = ProjectTaskList::create([
            'author_id' => $this->data['author_id'],
            'project_id' => $this->data['project_id'],
            'assignee_id' => $this->valueOrNull($this->data, 'assignee_id'),
            'title' => $this->data['title'],
            'description' => $this->valueOrNull($this->data, 'description'),
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
            'action' => 'project_task_list_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'project_task_list_id' => $this->projectTaskList->id,
                'project_task_list_title' => $this->projectTaskList->title,
            ]),
        ])->onQueue('low');
    }
}
