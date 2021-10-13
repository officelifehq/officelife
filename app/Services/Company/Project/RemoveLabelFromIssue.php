<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\ProjectIssue;
use App\Models\Company\ProjectLabel;
use App\Models\Company\ProjectMemberActivity;

class RemoveLabelFromIssue extends BaseService
{
    protected array $data;
    protected ProjectIssue $projectIssue;
    protected ProjectLabel $projectLabel;
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
            'project_issue_id' => 'required|integer|exists:project_issues,id',
            'project_label_id' => 'required|integer|exists:project_labels,id',
        ];
    }

    /**
     * Remove a project label from a project issue.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->remove();
        $this->logActivity();
        $this->log();
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

        $this->projectLabel = ProjectLabel::where('project_id', $this->data['project_id'])
            ->findOrFail($this->data['project_label_id']);

        $this->projectIssue = ProjectIssue::where('project_id', $this->data['project_id'])
            ->findOrFail($this->data['project_issue_id']);
    }

    private function remove(): void
    {
        $this->projectIssue->labels()->detach([$this->projectLabel->id]);
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
            'action' => 'project_label_removed_from_issue',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'issue_title' => $this->projectIssue->title,
                'label_name' => $this->projectLabel->name,
            ]),
        ])->onQueue('low');
    }
}
