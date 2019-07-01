<?php

namespace App\Services\Company\Task;

use App\Models\User\User;
use App\Models\Company\Task;
use App\Models\Company\Team;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Services\Company\Team\LogTeamAction;
use App\Services\Company\Employee\LogEmployeeAction;
use App\Services\Company\Adminland\Company\LogAuditAction;
use App\Services\User\Notification\CreateNotificationInUIForEmployee;

class CreateTask extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:users,id',
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
     * @return Task
     */
    public function execute(array $data) : Task
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr')
        );

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
            'author_id' => $author->id,
            'author_name' => $author->name,
            'task_id' => $task->id,
            'task_name' => $task->name,
        ];

        (new LogAuditAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'task_created',
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        if (! empty($data['team_id'])) {
            $this->addLogTeamAction($data, $dataToLog);
        }

        if (! empty($data['assignee_id'])) {
            $this->addLogEmployeeAction($data, $dataToLog, $author);
        }

        return $task;
    }

    /**
     * Actually create the task.
     *
     * @param array $data
     * @return Task
     */
    private function addTask(array $data) : Task
    {
        return Task::create([
            'company_id' => $data['company_id'],
            'author_id' => $data['author_id'],
            'team_id' => $this->nullOrValue($data, 'team_id'),
            'assignee_id' => $this->nullOrValue($data, 'assignee_id'),
            'completed' => $this->valueOrFalse($data, 'completed'),
            'title' => $data['title'],
            'due_at' => $this->valueOrFalse($data, 'due_at'),
            'completed_at' => $this->valueOrFalse($data, 'completed_at'),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);
    }

    /**
     * Create the team log.
     *
     * @param array $data
     * @param array $dataToLog
     * @return void
     */
    private function addLogTeamAction(array $data, array $dataToLog)
    {
        (new LogTeamAction)->execute([
            'company_id' => $data['company_id'],
            'team_id' => $data['team_id'],
            'action' => 'task_associated_to_team',
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);
    }

    /**
     * Create the employee log.
     *
     * @param array $data
     * @param array $dataToLog
     * @param User $user
     * @return void
     */
    private function addLogEmployeeAction(array $data, array $dataToLog, User $user)
    {
        (new LogEmployeeAction)->execute([
            'company_id' => $data['company_id'],
            'employee_id' => $data['assignee_id'],
            'action' => 'task_assigned_to_employee',
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        (new CreateNotificationInUIForEmployee)->execute([
            'user_id' => $user->id,
            'company_id' => $data['company_id'],
            'action' => 'task_assigned',
            'content' => $data['title'],
        ]);
    }
}
