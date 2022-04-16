<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ProjectBoard;
use App\Models\Company\ProjectSprint;
use App\Models\Company\ProjectMemberActivity;

class CreateProjectSprint extends BaseService
{
    protected array $data;
    protected Project $project;
    protected ProjectSprint $projectSprint;
    protected ProjectBoard $projectBoard;
    protected int $position;

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
            'project_board_id' => 'required|integer|exists:project_boards,id',
            'name' => 'required|string|max:255',
        ];
    }

    /**
     * Create a project sprint.
     *
     * @param array $data
     * @return ProjectSprint
     */
    public function execute(array $data): ProjectSprint
    {
        $this->data = $data;
        $this->validate();
        $this->determinePosition();
        $this->createSprint();
        $this->logActivity();
        $this->log();

        return $this->projectSprint;
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

        $this->projectBoard = ProjectBoard::where('project_id', $this->data['project_id'])
            ->findOrFail($this->data['project_board_id']);
    }

    private function createSprint(): void
    {
        $this->projectSprint = ProjectSprint::create([
            'project_id' => $this->data['project_id'],
            'project_board_id' => $this->data['project_board_id'],
            'author_id' => $this->data['author_id'],
            'name' => $this->data['name'],
            'position' => $this->position,
        ]);
    }

    /**
     * Position in the board is as follow: 0 is the highest position in the
     * backlog view.
     * New sprints are added at the top of the list - so they take position 0.
     * The sprint called "backlog" is always at the bottom of the page.
     * Only non started sprints are taken into account.
     */
    private function determinePosition(): void
    {
        // increment the position of all the existing sprints in the board
        DB::table('project_sprints')
            ->where('project_id', $this->project->id)
            ->where('project_board_id', $this->projectBoard->id)
            ->whereNull('started_at')
            ->whereNull('completed_at')
            ->increment('position');

        $this->position = 0;
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
            'action' => 'project_sprint_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'name' => $this->projectSprint->name,
            ]),
        ])->onQueue('low');
    }
}
