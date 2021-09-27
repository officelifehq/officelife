<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Comment;
use App\Models\Company\Project;
use App\Models\Company\ProjectMessage;
use App\Models\Company\ProjectMemberActivity;

class CreateProjectMessageComment extends BaseService
{
    protected array $data;
    protected ProjectMessage $projectMessage;
    protected Project $project;
    protected Comment $comment;

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
            'project_message_id' => 'required|integer|exists:project_messages,id',
            'content' => 'required|string|max:65535',
        ];
    }

    /**
     * Create a comment in the given project message.
     *
     * @param array $data
     * @return Comment
     */
    public function execute(array $data): Comment
    {
        $this->data = $data;
        $this->validate();
        $this->createComment();
        $this->logActivity();
        $this->log();

        return $this->comment;
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

        $this->projectMessage = ProjectMessage::where('project_id', $this->data['project_id'])
            ->findOrFail($this->data['project_message_id']);
    }

    private function createComment(): void
    {
        $this->comment = Comment::create([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->data['author_id'],
            'author_name' => $this->author->name,
            'content' => $this->data['content'],
        ]);

        $this->projectMessage->comments()->save($this->comment);
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
            'action' => 'project_message_comment_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
            ]),
        ])->onQueue('low');
    }
}
