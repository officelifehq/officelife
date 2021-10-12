<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\ProjectMemberActivity;
use App\Exceptions\ProjectCodeAlreadyExistException;

class UpdateProjectInformation extends BaseService
{
    private array $data;
    private Project $project;

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
            'code' => 'nullable|string|max:255',
            'short_code' => 'nullable|string|max:3',
            'summary' => 'nullable|string|max:255',
        ];
    }

    /**
     * Update project information.
     *
     * @param array $data
     * @return Project
     */
    public function execute(array $data): Project
    {
        $this->data = $data;
        $this->validate();
        $this->update();
        $this->logActivity();
        $this->log();

        return $this->project;
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

        // make sure the project code, if provided, is unique in the company
        if (! is_null($this->valueOrNull($this->data, 'code'))) {
            $count = Project::where('company_id', $this->data['company_id'])
                ->where('code', $this->data['code'])
                ->where('id', '!=', $this->project->id)
                ->count();

            if ($count > 0) {
                throw new ProjectCodeAlreadyExistException();
            }
        }

        // make sure the project short code, if provided, is unique in the company
        if (! is_null($this->valueOrNull($this->data, 'short_code'))) {
            $count = Project::where('company_id', $this->data['company_id'])
                ->where('short_code', $this->data['short_code'])
                ->where('id', '!=', $this->project->id)
                ->count();

            if ($count > 0) {
                throw new ProjectCodeAlreadyExistException();
            }
        }
    }

    private function update(): void
    {
        $this->project->name = $this->data['name'];
        $this->project->code = $this->valueOrNull($this->data, 'code');
        $this->project->short_code = $this->valueOrNull($this->data, 'short_code');
        $this->project->summary = $this->valueOrNull($this->data, 'summary');
        $this->project->save();
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
            'action' => 'project_information_updated',
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
