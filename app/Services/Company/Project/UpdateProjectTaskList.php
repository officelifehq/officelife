<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\ProjectTaskList;
use App\Models\Company\ProjectMemberActivity;

class UpdateProjectTaskList extends BaseService
{
    protected array $data;

    protected Project $project;

    protected ProjectTaskList $projectTaskList;

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
            'project_task_list_id' => 'nullable|integer|exists:project_task_lists,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
        ];
    }

    /**
     * Update the project task list.
     *
     * @param array $data
     * @return ProjectTaskList
     */
    public function execute(array $data): ProjectTaskList
    {
        $this->data = $data;
        $this->validate();
        $this->update();
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

        $this->projectTaskList = ProjectTaskList::where('project_id', $this->project->id)
            ->findOrFail($this->data['project_task_list_id']);
    }

    private function update(): void
    {
        $this->projectTaskList->title = $this->data['title'];
        $this->projectTaskList->description = $this->valueOrNull($this->data, 'description');
        $this->projectTaskList->save();
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
            'action' => 'project_task_list_updated',
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
