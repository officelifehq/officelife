<?php

namespace App\Services\Company\Project;

use App\Services\BaseService;
use App\Models\Company\Project;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ProjectSprint;
use App\Models\Company\ProjectMemberActivity;

class ToggleProjectSprint extends BaseService
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
     * Collapse or expand the project sprint.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->toggle();
        $this->logActivity();
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

    private function toggle(): void
    {
        $record = DB::table('project_sprint_employee_settings')
            ->where('project_sprint_id', $this->projectSprint->id)
            ->where('employee_id', $this->author->id)
            ->first();

        if ($record) {
            DB::table('project_sprint_employee_settings')
                ->where('project_sprint_id', $this->projectSprint->id)
                ->where('employee_id', $this->author->id)
                ->update([
                    'collapsed' => ! $record->collapsed,
                ]);
        } else {
            DB::table('project_sprint_employee_settings')
                ->insert([
                    'project_sprint_id' => $this->projectSprint->id,
                    'employee_id' => $this->author->id,
                    'collapsed' => true,
                ]);
        }
    }

    private function logActivity(): void
    {
        ProjectMemberActivity::create([
            'project_id' => $this->project->id,
            'employee_id' => $this->author->id,
        ]);
    }
}
