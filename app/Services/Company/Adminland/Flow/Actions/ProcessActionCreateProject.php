<?php

namespace App\Services\Company\Adminland\Flow\Actions;

use App\Models\Company\Action;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Services\Company\Project\CreateProject;
use App\Exceptions\MissingInformationInJsonAction;

class ProcessActionCreateProject
{
    private array $data;
    private Employee $employee;
    private Project $project;

    /**
     * Create a project, in the context of an action.
     *
     * @param array $data
     * @return Project
     */
    public function execute(array $data): Project
    {
        $this->data = $data;
        $this->employee = $data['employee'];

        $this->validate();
        $this->validateJsonStructure();
        $this->createProject();

        return $this->project;
    }

    private function validate(): void
    {
        if ($this->employee->locked) {
            return;
        }
    }

    private function validateJsonStructure(): void
    {
        $keys = [
            'project_name',
            'project_summary',
            'project_description',
            'project_project_lead_id',
        ];

        foreach ($keys as $key) {
            if (! array_key_exists($key, json_decode($this->data['content'], true))) {
                throw new MissingInformationInJsonAction();
            }
        }
    }

    private function createProject(): void
    {
        $content = json_decode($this->data['content']);

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
