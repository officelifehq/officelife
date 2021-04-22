<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use Illuminate\Validation\Rule;
use App\Models\Company\ProjectLink;
use App\Models\Company\ProjectMemberActivity;

class CreateProjectLink extends BaseService
{
    protected array $data;

    protected ProjectLink $projectLink;

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
            'type' => [
                'required',
                Rule::in([
                    'slack',
                    'email',
                    'url',
                ]),
            ],
            'label' => 'nullable|string|max:255',
            'url' => 'required|string|max:255',
        ];
    }

    /**
     * Create a project link.
     *
     * @param array $data
     * @return ProjectLink
     */
    public function execute(array $data): ProjectLink
    {
        $this->data = $data;
        $this->validate();
        $this->createLink();
        $this->logActivity();
        $this->log();

        return $this->projectLink;
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

    private function createLink(): void
    {
        $this->projectLink = ProjectLink::create([
            'project_id' => $this->data['project_id'],
            'label' => $this->valueOrNull($this->data, 'label'),
            'type' => $this->data['type'],
            'url' => $this->data['url'],
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
            'action' => 'project_link_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_link_id' => $this->projectLink->id,
                'project_link_name' => $this->projectLink->label,
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
            ]),
        ])->onQueue('low');
    }
}
