<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\ProjectMessage;
use App\Models\Company\ProjectMemberActivity;

class UpdateProjectMessage extends BaseService
{
    protected array $data;
    protected Project $project;
    protected ProjectMessage $projectMessage;

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
            'project_message_id' => 'nullable|integer|exists:project_messages,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:65535',
        ];
    }

    /**
     * Update the project message.
     *
     * @param array $data
     * @return ProjectMessage
     */
    public function execute(array $data): ProjectMessage
    {
        $this->data = $data;
        $this->validate();
        $this->update();
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

        $this->projectMessage = ProjectMessage::where('project_id', $this->project->id)
            ->findOrFail($this->data['project_message_id']);
    }

    private function update(): void
    {
        $this->projectMessage->title = $this->data['title'];
        $this->projectMessage->content = $this->data['content'];
        $this->projectMessage->save();
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
            'action' => 'project_message_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'project_message_id' => $this->projectMessage->id,
                'project_message_title' => $this->projectMessage->title,
            ]),
        ])->onQueue('low');
    }
}
