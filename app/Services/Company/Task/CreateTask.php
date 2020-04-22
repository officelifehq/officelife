<?php

namespace App\Services\Company\Task;

use Carbon\Carbon;
use App\Jobs\LogTeamAudit;
use App\Jobs\NotifyEmployee;
use App\Models\Company\Task;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;

class CreateTask extends BaseService
{
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
            'team_id' => 'nullable|integer|exists:teams,id',
            'assignee_id' => 'nullable|integer|exists:employees,id',
            'completed' => 'nullable|boolean',
            'title' => 'required|string|max:255',
            'due_at' => 'nullable|date_format:Y-m-d',
            'completed_at' => 'nullable|date_format:Y-m-d',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Create a task.
     *
     * @param array $data
     *
     * @return Task
     */
    public function execute(array $data): Task
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        if (! empty($data['team_id'])) {
            Team::where('company_id', $data['company_id'])
                ->findOrFail($data['team_id']);
        }

        if (! empty($data['assignee_id'])) {
            Employee::where('company_id', $data['company_id'])
                ->findOrFail($data['assignee_id']);
        }

        $task = $this->addTask($data);

        $dataToLog = [
            'task_id' => $task->id,
            'task_name' => $task->name,
        ];

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'task_created',
            'objects' => json_encode($dataToLog),
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        if (! empty($data['team_id'])) {
            $this->addLogTeamAction($data, $dataToLog);
        }

        if (! empty($data['assignee_id'])) {
            $this->addLogEmployeeAction($data, $dataToLog);
        }

        return $task;
    }

    /**
     * Actually create the task.
     *
     * @param array $data
     *
     * @return Task
     */
    private function addTask(array $data): Task
    {
        return Task::create([
            'company_id' => $data['company_id'],
            'team_id' => $this->valueOrNull($data, 'team_id'),
            'assignee_id' => $this->valueOrNull($data, 'assignee_id'),
            'completed' => $this->valueOrFalse($data, 'completed'),
            'title' => $data['title'],
            'due_at' => $this->nullOrDate($data, 'due_at'),
            'completed_at' => $this->nullOrDate($data, 'completed_at'),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);
    }

    /**
     * Create the team log.
     *
     * @param array $data
     * @param array $dataToLog
     */
    private function addLogTeamAction(array $data, array $dataToLog): void
    {
        LogTeamAudit::dispatch([
            'team_id' => $data['team_id'],
            'action' => 'task_associated_to_team',
            'objects' => json_encode($dataToLog),
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');
    }

    /**
     * Create the employee log.
     *
     * @param array $data
     * @param array $dataToLog
     */
    private function addLogEmployeeAction(array $data, array $dataToLog): void
    {
        LogEmployeeAudit::dispatch([
            'employee_id' => $data['assignee_id'],
            'action' => 'task_assigned_to_employee',
            'objects' => json_encode($dataToLog),
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        NotifyEmployee::dispatch([
            'employee_id' => $data['assignee_id'],
            'action' => 'task_assigned',
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');
    }
}
