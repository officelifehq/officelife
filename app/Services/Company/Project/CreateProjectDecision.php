<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectDecision;
use App\Models\Company\ProjectMemberActivity;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateProjectDecision extends BaseService
{
    protected array $data;

    protected ProjectDecision $projectDecision;

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
            'decided_at' => 'nullable|date_format:Y-m-d',
            'deciders' => 'nullable|array',
        ];
    }

    /**
     * Create a project decision.
     *
     * @param array $data
     * @return ProjectDecision
     */
    public function execute(array $data): ProjectDecision
    {
        $this->data = $data;
        $this->validate();
        $this->createDecision();
        $this->attachEmployees();
        $this->logActivity();
        $this->log();

        return $this->projectDecision;
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

    private function createDecision(): void
    {
        $this->projectDecision = ProjectDecision::create([
            'project_id' => $this->data['project_id'],
            'author_id' => $this->data['author_id'],
            'title' => $this->data['title'],
            'decided_at' => $this->valueOrNow($this->data, 'decided_at'),
        ]);
    }

    private function attachEmployees(): void
    {
        if (! $this->data['deciders']) {
            return;
        }

        foreach ($this->data['deciders'] as $key => $employeeId) {

            // make sure the employee exists and is in the company
            try {
                $employee = Employee::where('company_id', $this->data['company_id'])
                    ->findOrFail($employeeId);
            } catch (ModelNotFoundException $e) {
                continue;
            }

            $this->projectDecision->deciders()->syncWithoutDetaching([
                $employee->id,
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
            'action' => 'project_decision_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'title' => $this->projectDecision->title,
            ]),
        ])->onQueue('low');
    }
}
