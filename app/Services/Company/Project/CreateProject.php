<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Exceptions\ProjectCodeAlreadyExistException;

class CreateProject extends BaseService
{
    protected array $data;
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
            'project_lead_id' => 'nullable|integer|exists:employees,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'summary' => 'nullable|string|max:255',
            'emoji' => 'nullable|string|max:5',
            'description' => 'nullable|string|max:65535',
        ];
    }

    /**
     * Create a project.
     *
     * @param array $data
     * @return Project
     */
    public function execute(array $data): Project
    {
        $this->data = $data;
        $this->validate();
        $this->createProject();
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

        // make sure the project code, if provided, is unique in the company
        if (! is_null($this->valueOrNull($this->data, 'code'))) {
            $count = Project::where('company_id', $this->data['company_id'])
                ->where('code', $this->data['code'])
                ->count();

            if ($count > 0) {
                throw new ProjectCodeAlreadyExistException();
            }
        }

        if (! is_null($this->valueOrNull($this->data, 'project_lead_id'))) {
            Employee::where('company_id', $this->data['company_id'])
                ->findOrFail($this->data['project_lead_id']);
        }
    }

    private function createProject(): void
    {
        $this->project = Project::create([
            'company_id' => $this->data['company_id'],
            'project_lead_id' => $this->valueOrNull($this->data, 'project_lead_id'),
            'name' => $this->data['name'],
            'summary' => $this->valueOrNull($this->data, 'summary'),
            'status' => Project::CREATED,
            'code' => $this->valueOrNull($this->data, 'code'),
            'emoji' => $this->valueOrNull($this->data, 'emoji'),
            'description' => $this->valueOrNull($this->data, 'description'),
        ]);

        if (! is_null($this->valueOrNull($this->data, 'project_lead_id'))) {
            (new AddEmployeeToProject)->execute([
                'company_id' => $this->data['company_id'],
                'author_id' => $this->data['author_id'],
                'project_id' => $this->project->id,
                'employee_id' => $this->data['project_lead_id'],
            ]);
        }
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'project_created',
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
