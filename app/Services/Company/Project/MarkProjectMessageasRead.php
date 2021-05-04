<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Services\BaseService;
use App\Models\Company\Project;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ProjectMessage;
use App\Models\Company\ProjectMemberActivity;

class MarkProjectMessageasRead extends BaseService
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
            'project_message_id' => 'required|integer|exists:project_messages,id',
        ];
    }

    /**
     * Mark a project message as read.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->read();
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

        $this->projectMessage = ProjectMessage::where('project_id', $this->data['project_id'])
            ->findOrFail($this->data['project_message_id']);
    }

    private function read(): void
    {
        $count = DB::table('project_message_read_status')
            ->where('project_message_id', $this->projectMessage->id)
            ->where('employee_id', $this->author->id)
            ->count();

        if ($count > 0) {
            return;
        }

        DB::table('project_message_read_status')->insert([
            'project_message_id' => $this->projectMessage->id,
            'employee_id' => $this->author->id,
            'created_at' => Carbon::now(),
        ]);
    }

    private function logActivity(): void
    {
        ProjectMemberActivity::create([
            'project_id' => $this->project->id,
            'employee_id' => $this->author->id,
        ]);
    }
}
