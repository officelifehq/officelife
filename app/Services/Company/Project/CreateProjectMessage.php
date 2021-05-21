<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\ProjectMessage;
use App\Models\Company\ProjectMemberActivity;

class CreateProjectMessage extends BaseService
{
    protected array $data;

    protected ProjectMessage $projectMessage;

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
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:65535',
        ];
    }

    /**
     * Create a project message.
     *
     * @param array $data
     * @return ProjectMessage
     */
    public function execute(array $data): ProjectMessage
    {
        $this->data = $data;
        $this->validate();
        $this->createMessage();
        $this->markAsReadForThisUser();
        $this->logActivity();
        $this->log();

        return $this->projectMessage;
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

    private function createMessage(): void
    {
        $this->projectMessage = ProjectMessage::create([
            'project_id' => $this->data['project_id'],
            'author_id' => $this->data['author_id'],
            'title' => $this->data['title'],
            'content' => $this->data['content'],
        ]);
    }

    private function markAsReadForThisUser(): void
    {
        (new MarkProjectMessageasRead)->execute([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->data['author_id'],
            'project_id' => $this->data['project_id'],
            'project_message_id' => $this->projectMessage->id,
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
            'action' => 'project_message_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'title' => $this->projectMessage->title,
            ]),
        ])->onQueue('low');
    }
}
