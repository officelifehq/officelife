<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\ProjectIssue;
use App\Models\Company\ProjectMemberActivity;

class UpdateProjectIssue extends BaseService
{
    protected array $data;
    protected Project $project;
    protected ProjectIssue $projectIssue;

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
            'project_issue_id' => 'nullable|integer|exists:project_issues,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:16777215',
            'points' => 'nullable|integer|max:100',
        ];
    }

    /**
     * Update the project issue.
     *
     * @param array $data
     * @return ProjectIssue
     */
    public function execute(array $data): ProjectIssue
    {
        $this->data = $data;
        $this->validate();
        $this->update();
        $this->logActivity();
        $this->log();

        return $this->projectIssue;
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

        $this->projectIssue = ProjectIssue::where('project_id', $this->project->id)
            ->findOrFail($this->data['project_issue_id']);
    }

    private function update(): void
    {
        $this->projectIssue->title = $this->data['title'];
        $this->projectIssue->slug = Str::of($this->data['title'])->slug('-');
        $this->projectIssue->description = $this->valueOrNull($this->data, 'description');
        $this->projectIssue->story_points = $this->valueOrNull($this->data, 'story_points');
        $this->projectIssue->save();
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
            'action' => 'project_issue_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'project_issue_id' => $this->projectIssue->id,
                'project_issue_title' => $this->projectIssue->title,
            ]),
        ])->onQueue('low');
    }
}
