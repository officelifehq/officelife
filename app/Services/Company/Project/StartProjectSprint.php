<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\ProjectSprint;
use App\Models\Company\ProjectMemberActivity;

class StartProjectSprint extends BaseService
{
    protected array $data;
    protected Project $project;
    protected ProjectSprint $projectSprint;

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
            'project_sprint_id' => 'nullable|integer|exists:project_sprints,id',
        ];
    }

    /**
     * Start the project sprint.
     *
     * @param array $data
     * @return ProjectSprint
     */
    public function execute(array $data): ProjectSprint
    {
        $this->data = $data;
        $this->validate();
        $this->start();
        $this->logActivity();
        $this->log();

        return $this->projectSprint;
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

        $this->projectSprint = ProjectSprint::where('project_id', $this->project->id)
            ->findOrFail($this->data['project_sprint_id']);
    }

    private function start(): void
    {
        $this->projectSprint->active = true;
        $this->projectSprint->started_at = Carbon::now();
        $this->projectSprint->save();
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
            'action' => 'project_sprint_started',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'project_sprint_id' => $this->projectSprint->id,
                'project_sprint_name' => $this->projectSprint->name,
            ]),
        ])->onQueue('low');
    }
}
