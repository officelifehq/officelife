<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Models\Company\File;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\ProjectMemberActivity;

class DestroyProjectFile extends BaseService
{
    protected array $data;
    protected File $file;
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
            'file_id' => 'required|integer|exists:files,id',
        ];
    }

    /**
     * Destroy a file in the project.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->destroyFile();
        $this->logActivity();
        $this->log();
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

        $this->file = File::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['file_id']);
    }

    private function destroyFile(): void
    {
        /* @phpstan-ignore-next-line */
        $this->project->files()->detach($this->data['file_id']);
        $this->file->delete();
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
            'action' => 'project_file_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'name' => $this->file->name,
            ]),
        ])->onQueue('low');
    }
}
