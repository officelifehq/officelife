<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Project;
use App\Models\Company\ProjectLabel;
use App\Models\Company\ProjectMemberActivity;
use App\Exceptions\LabelAlreadyExistException;

class UpdateProjectLabel extends BaseService
{
    protected array $data;
    protected Project $project;
    protected ProjectLabel $projectLabel;

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
            'project_label_id' => 'required|integer|exists:project_labels,id',
            'name' => 'required|string|max:255',
        ];
    }

    /**
     * Update the project label.
     *
     * @param array $data
     * @return ProjectLabel
     */
    public function execute(array $data): ProjectLabel
    {
        $this->data = $data;
        $this->validate();
        $this->verifyLabelUniqueness();
        $this->update();
        $this->logActivity();
        $this->log();

        return $this->projectLabel;
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

        $this->projectLabel = ProjectLabel::where('project_id', $this->project->id)
            ->findOrFail($this->data['project_label_id']);
    }

    private function verifyLabelUniqueness(): void
    {
        $alreadyExists = ProjectLabel::where('project_id', $this->data['project_id'])
            ->where('name', $this->data['name'])
            ->where('id', '!=', $this->projectLabel->id)
            ->exists();

        if ($alreadyExists) {
            throw new LabelAlreadyExistException();
        }
    }

    private function update(): void
    {
        $this->projectLabel->name = $this->data['name'];
        $this->projectLabel->save();
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
            'action' => 'project_label_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'name' => $this->projectLabel->name,
            ]),
        ])->onQueue('low');
    }
}
