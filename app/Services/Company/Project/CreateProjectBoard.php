<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\ProjectBoard;
use App\Models\Company\ProjectMemberActivity;

class CreateProjectBoard extends BaseService
{
    protected array $data;
    protected ProjectBoard $projectBoard;
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
            'name' => 'required|string|max:255',
        ];
    }

    /**
     * Create a project board.
     *
     * @param array $data
     * @return ProjectBoard
     */
    public function execute(array $data): ProjectBoard
    {
        $this->data = $data;
        $this->validate();
        $this->createBoard();
        $this->createBacklog();
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
    }

    private function createBoard(): void
    {
        $this->projectBoard = ProjectBoard::create([
            'project_id' => $this->data['project_id'],
            'author_id' => $this->data['author_id'],
            'name' => $this->data['name'],
        ]);
    }

    private function createBacklog(): void
    {
        $sprint = (new CreateProjectSprint)->execute([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->data['author_id'],
            'project_id' => $this->data['project_id'],
            'project_board_id' => $this->projectBoard->id,
            'name' => 'Backlog',
        ]);
        $sprint->is_board_backlog = true;
        $sprint->save();
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
            'action' => 'project_board_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'name' => $this->projectBoard->name,
            ]),
        ])->onQueue('low');
    }
}
