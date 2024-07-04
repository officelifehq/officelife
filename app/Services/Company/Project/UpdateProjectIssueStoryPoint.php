<?php

namespace App\Services\Company\Project;

use App\Jobs\LogAccountAudit;
use App\Models\Company\Project;
use App\Models\Company\ProjectIssue;
use App\Models\Company\ProjectMemberActivity;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateProjectIssueStoryPoint extends BaseService
{
    protected array $data;

    protected Project $project;

    protected ProjectIssue $projectIssue;

    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'project_id' => 'nullable|integer|exists:projects,id',
            'project_issue_id' => 'nullable|integer|exists:project_issues,id',
            'points' => 'nullable|integer|max:100',
        ];
    }

    /**
     * Update the story point of the given project issue.
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->update();
        $this->recordHistory();
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

        $this->projectIssue = ProjectIssue::where('project_id', $this->project->id)
            ->findOrFail($this->data['project_issue_id']);
    }

    private function update(): void
    {
        $this->projectIssue->story_points = $this->valueOrNull($this->data, 'points');
        $this->projectIssue->save();
    }

    private function recordHistory(): void
    {
        DB::table('project_issue_story_points_history')->insert([
            'project_issue_id' => $this->projectIssue->id,
            'employee_id' => $this->author->id,
            'story_points' => $this->valueOrNull($this->data, 'points'),
            'created_at' => Carbon::now(),
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
            'action' => 'project_issue_story_point_updated',
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
