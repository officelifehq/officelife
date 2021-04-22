<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\ProjectStatus;
use App\Models\Company\ProjectMemberActivity;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateProjectStatus extends BaseService
{
    protected array $data;

    protected ProjectStatus $projectStatus;

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
            'status' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:65535',
        ];
    }

    /**
     * Create a project status.
     *
     * @param array $data
     * @return ProjectStatus
     */
    public function execute(array $data): ProjectStatus
    {
        $this->data = $data;
        $this->validate();
        $this->createStatus();
        $this->logActivity();
        $this->log();

        return $this->projectStatus;
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

        if (! $this->author->isInProject($this->data['project_id'])) {
            throw new ModelNotFoundException();
        }
    }

    private function createStatus(): void
    {
        $this->projectStatus = ProjectStatus::create([
            'project_id' => $this->data['project_id'],
            'author_id' => $this->data['author_id'],
            'status' => $this->data['status'],
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
            'action' => 'project_status_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
            ]),
        ])->onQueue('low');
    }
}
