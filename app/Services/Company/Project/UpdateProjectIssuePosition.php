<?php

namespace App\Services\Company\Project;

use App\Services\BaseService;
use App\Models\Company\Project;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ProjectIssue;
use App\Models\Company\ProjectSprint;
use App\Models\Company\ProjectMemberActivity;

class UpdateProjectIssuePosition extends BaseService
{
    protected array $data;
    protected Project $project;
    protected ProjectIssue $projectIssue;
    protected ProjectSprint $projectSprint;
    protected int $pastPosition;

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
            'project_sprint_id' => 'required|integer|exists:project_sprints,id',
            'project_issue_id' => 'required|integer|exists:project_issues,id',
            'new_position' => 'required|integer',
        ];
    }

    /**
     * Update the project issue order.
     *
     * @param array $data
     * @return ProjectIssue
     */
    public function execute(array $data): ProjectIssue
    {
        $this->data = $data;
        $this->validate();
        $this->updateOrder();
        $this->logActivity();

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

        $this->projectSprint = ProjectSprint::where('project_id', $this->project->id)
            ->findOrFail($this->data['project_sprint_id']);

        $this->pastPosition = DB::table('project_issue_project_sprint')
            ->where('project_sprint_id', $this->projectSprint->id)
            ->where('project_issue_id', $this->projectIssue->id)
            ->select('position')
            ->first()->position;
    }

    /**
     * Update the order of the all the issues affected by the new order.
     */
    private function updateOrder(): void
    {
        if ($this->data['new_position'] > $this->pastPosition) {
            $this->updateAscendingPosition();
        } else {
            $this->updateDescendingPosition();
        }

        DB::table('project_issue_project_sprint')
            ->where('project_sprint_id', $this->projectSprint->id)
            ->where('project_issue_id', $this->projectIssue->id)
            ->update([
                'position' => $this->data['new_position'],
            ]);
    }

    private function updateAscendingPosition(): void
    {
        DB::table('project_issue_project_sprint')
            ->where('project_sprint_id', $this->projectSprint->id)
            ->where('position', '>', $this->pastPosition)
            ->where('position', '<=', $this->data['new_position'])
            ->decrement('position');
    }

    private function updateDescendingPosition(): void
    {
        DB::table('project_issue_project_sprint')
            ->where('project_sprint_id', $this->projectSprint->id)
            ->where('position', '>=', $this->data['new_position'])
            ->where('position', '<', $this->pastPosition)
            ->increment('position');
    }

    private function logActivity(): void
    {
        ProjectMemberActivity::create([
            'project_id' => $this->project->id,
            'employee_id' => $this->author->id,
        ]);
    }
}
