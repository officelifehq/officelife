<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\IssueType;
use App\Models\Company\ProjectIssue;
use App\Models\Company\ProjectMemberActivity;

class CreateProjectIssue extends BaseService
{
    protected array $data;
    protected ProjectIssue $projectIssue;
    protected IssueType $issueType;
    protected Project $project;
    protected ?int $newIdInProject;
    protected string $key;

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
            'issue_type_id' => 'required|integer|exists:issue_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:16777215',
        ];
    }

    /**
     * Create a project issue.
     *
     * @param array $data
     * @return ProjectIssue
     */
    public function execute(array $data): ProjectIssue
    {
        $this->data = $data;
        $this->validate();
        $this->generateIssueKey();
        $this->createIssue();
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

        $this->issueType = IssueType::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['issue_type_id']);
    }

    private function generateIssueKey(): void
    {
        $projectShortCode = $this->project->short_code;

        // get the biggest key number used in this project
        $newId = ProjectIssue::where('project_id', $this->data['project_id'])
            ->max('id_in_project');

        $newId++;
        $this->newIdInProject = $newId;

        $this->key = $projectShortCode.'-'.$this->newIdInProject;
    }

    private function createIssue(): void
    {
        $this->projectIssue = ProjectIssue::create([
            'project_id' => $this->data['project_id'],
            'reporter_id' => $this->data['author_id'],
            'issue_type_id' => $this->data['issue_type_id'],
            'id_in_project' => $this->newIdInProject,
            'key' => $this->key,
            'title' => $this->data['title'],
            'slug' => Str::of($this->data['title'])->slug('-'),
            'description' => $this->valueOrNull($this->data, 'description'),
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
            'action' => 'project_issue_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'title' => $this->projectIssue->title,
            ]),
        ])->onQueue('low');
    }
}
