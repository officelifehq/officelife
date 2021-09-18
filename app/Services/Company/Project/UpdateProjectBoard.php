<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\ProjectBoard;
use App\Models\Company\ProjectMemberActivity;

class UpdateProjectBoard extends BaseService
{
    protected array $data;
    protected Project $project;
    protected ProjectBoard $projectBoard;

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
            'project_board_id' => 'nullable|integer|exists:project_boards,id',
            'name' => 'required|string|max:255',
        ];
    }

    /**
     * Update the project board.
     *
     * @param array $data
     * @return ProjectBoard
     */
    public function execute(array $data): ProjectBoard
    {
        $this->data = $data;
        $this->validate();
        $this->update();
        $this->logActivity();
        $this->log();

        return $this->projectBoard;
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

        $this->projectBoard = ProjectBoard::where('project_id', $this->project->id)
            ->findOrFail($this->data['project_board_id']);
    }

    private function update(): void
    {
        $this->projectBoard->name = $this->data['name'];
        $this->projectBoard->save();
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
            'action' => 'project_board_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'project_board_id' => $this->projectBoard->id,
                'project_board_name' => $this->projectBoard->name,
            ]),
        ])->onQueue('low');
    }
}
