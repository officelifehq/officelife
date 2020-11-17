<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\ProjectTask;

class UpdateProjectTask extends BaseService
{
    protected array $data;

    protected Project $project;

    protected ProjectTask $projectTask;

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
            'project_id' => 'nullable|integer|exists:projects,id',
            'project_task_id' => 'nullable|integer|exists:project_tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
        ];
    }

    /**
     * Update the project task.
     *
     * @param array $data
     * @return ProjectTask
     */
    public function execute(array $data): ProjectTask
    {
        $this->data = $data;
        $this->validate();
        $this->update();
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

        $this->projectTask = ProjectTask::where('project_id', $this->project->id)
            ->findOrFail($this->data['project_task_id']);
    }

    private function update(): void
    {
        $this->projectTask->title = $this->data['title'];
        $this->projectTask->description = $this->valueOrNull($this->data, 'description');
        $this->projectTask->save();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'project_task_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'project_task_id' => $this->projectTask->id,
                'project_task_title' => $this->projectTask->title,
            ]),
        ])->onQueue('low');
    }
}
