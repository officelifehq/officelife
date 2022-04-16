<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ProjectIssue;
use App\Models\Company\ProjectSprint;
use App\Models\Company\ProjectMemberActivity;

class DestroyProjectIssue extends BaseService
{
    protected array $data;
    protected Project $project;
    protected ProjectSprint $projectSprint;
    protected ProjectIssue $projectIssue;
    protected int $issuePosition;

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
            'project_sprint_id' => 'required|integer|exists:project_sprints,id',
            'project_issue_id' => 'nullable|integer|exists:project_issues,id',
        ];
    }

    /**
     * Delete the project issue.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->destroy();
        $this->reorderIssuesInSprint();
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

        $this->projectSprint = ProjectSprint::where('project_id', $this->data['project_id'])
            ->findOrFail($this->data['project_sprint_id']);

        $this->issuePosition = DB::table('project_issue_project_sprint')
            ->where('project_sprint_id', $this->projectSprint->id)
            ->where('project_issue_id', $this->projectIssue->id)
            ->select('position')
            ->first()->position;
    }

    private function destroy(): void
    {
        $this->projectIssue->delete();
    }

    /**
     * An issue has a position in the sprint (backlog or "real" sprint).
     * Position 0 is the highest position in the sprint.
     */
    private function reorderIssuesInSprint(): void
    {
        DB::table('project_issue_project_sprint')
            ->where('project_sprint_id', $this->projectSprint->id)
            ->where('position', '>', $this->issuePosition)
            ->decrement('position');
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
            'action' => 'project_issue_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'project_issue_title' => $this->projectIssue->title,
            ]),
        ])->onQueue('low');
    }
}
