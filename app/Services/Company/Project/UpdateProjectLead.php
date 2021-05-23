<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectMemberActivity;

class UpdateProjectLead extends BaseService
{
    private array $data;

    private Employee $employee;

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
            'employee_id' => 'required|integer|exists:employees,id',
        ];
    }

    /**
     * Update project lead.
     *
     * @param array $data
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->data = $data;
        $this->validate();
        $this->updateLead();
        $this->logActivity();
        $this->log();

        return $this->employee;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($this->data);

        $this->project = Project::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['project_id']);
    }

    private function updateLead(): void
    {
        $this->project->project_lead_id = $this->data['employee_id'];
        $this->project->save();

        // check if the new lead is part of the project - if not, add him
        if ($this->employee->isInProject($this->project->id)) {
            return;
        } else {
            $this->project->employees()->syncWithoutDetaching([
                $this->data['employee_id'] => [
                    'role' => trans('project.project_title_lead'),
                ],
            ]);
        }
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
            'action' => 'project_team_lead_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'project_team_lead_updated',
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
