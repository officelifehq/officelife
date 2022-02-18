<?php

namespace App\Services\Company\Adminland\Flow\Actions;

use App\Models\Company\Action;
use App\Models\Company\Employee;
use App\Services\BaseServiceAction;
use App\Models\Company\ScheduledAction;
use App\Services\Company\Task\CreateTask;

class ProcessActionCreateTask extends BaseServiceAction
{
    private Employee $employee;
    private ScheduledAction $scheduledAction;

    /**
     * Get the keys that should be in the JSON.
     *
     * @return array
     */
    public function keys(): array
    {
        return [
            'task_name',
        ];
    }

    /**
     * Create a task, in the context of an action.
     * The service expects an array containing:
     * - the Employee object.
     *
     * @param ScheduledAction $scheduledAction
     */
    public function execute(ScheduledAction $scheduledAction): void
    {
        $this->employee = $scheduledAction->employee;
        $this->scheduledAction = $scheduledAction;

        if ($this->employee->locked) {
            return;
        }

        if ($this->scheduledAction->processed) {
            return;
        }

        $this->validateJsonStructure($this->scheduledAction);
        $this->createTask();
        $this->markAsProcessed($this->scheduledAction);
        $this->scheduleFutureIteration($scheduledAction->action, $this->employee);
    }

    private function createTask(): void
    {
        $content = json_decode($this->scheduledAction->content);

        (new CreateTask)->execute([
            'company_id' => $this->employee->company_id,
            'author_id' => $this->employee->id,
            'employee_id' => $this->employee->id,
            'title' => $content->{'task_name'},
        ]);
    }
}
