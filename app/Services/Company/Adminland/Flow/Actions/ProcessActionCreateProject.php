<?php

namespace App\Services\Company\Adminland\Flow\Actions;

use App\Models\Company\Action;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Services\BaseServiceAction;
use App\Models\Company\ScheduledAction;
use App\Services\Company\Project\CreateProject;

class ProcessActionCreateProject extends BaseServiceAction
{
    private array $data;
    private Employee $employee;
    private Project $project;
    private ScheduledAction $scheduledAction;

    /**
     * Get the keys that should be in the JSON.
     *
     * @return array
     */
    public function keys(): array
    {
        return [
            'project_name',
            'project_summary',
            'project_description',
            'project_project_lead_id',
        ];
    }

    /**
     * Create a project, in the context of an action.
     *
     * @param ScheduledAction $scheduledAction
     * @return Project|null
     */
    public function execute(ScheduledAction $scheduledAction): ?Project
    {
        $this->employee = $scheduledAction->employee;
        $this->scheduledAction = $scheduledAction;

        if ($this->employee->locked) {
            return null;
        }

        if ($this->scheduledAction->processed) {
            return null;
        }

        $this->validateJsonStructure($this->scheduledAction);
        $this->createProject();
        $this->markAsProcessed($this->scheduledAction);
        $this->scheduleFutureIteration($scheduledAction->action, $this->employee);

        return $this->project;
    }

    private function createProject(): void
    {
        $content = json_decode($this->scheduledAction->content);

        $this->project = (new CreateProject)->execute([
            'company_id' => $this->employee->company_id,
            'author_id' => $this->employee->id,
            'project_lead_id' => $content->{'project_project_lead_id'},
            'name' => $content->{'project_name'},
            'summary' => $content->{'project_summary'},
            'description' => $content->{'project_description'},
        ]);
    }
}
