<?php

namespace App\Services\Company\Task;

use Carbon\Carbon;
use App\Jobs\NotifyEmployee;
use App\Models\Company\Task;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;

class CreateTask extends BaseService
{
    protected array $data;

    protected Task $task;

    protected Employee $employee;

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
            'employee_id' => 'required|integer|exists:employees,id',
            'title' => 'required|string|max:255',
            'completed' => 'nullable|boolean',
            'due_at' => 'nullable|date_format:Y-m-d',
            'completed_at' => 'nullable|date_format:Y-m-d',
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
            ->asNormalUser()
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($data);
        $this->data = $data;

        $this->createTask();
        $this->log();

        if ($this->data['employee_id'] != $this->data['author_id']) {
            // This means the author has actually assigned the task to the
            // employee and not for himself, so we need to warn the employee.
            $this->warnAboutTaskAssignment();
        }

        return $this->task;
    }

    /**
     * Actually create the task.
     */
    private function createTask(): void
    {
        $this->task = Task::create([
            'employee_id' => $this->employee->id,
            'completed' => $this->valueOrFalse($this->data, 'completed'),
            'title' => $this->data['title'],
            'due_at' => $this->nullOrDate($this->data, 'due_at'),
            'completed_at' => $this->nullOrDate($this->data, 'completed_at'),
        ]);
    }

    /**
     * Create the audit log.
     */
    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'task_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
                'title' => $this->data['title'],
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->data['employee_id'],
            'action' => 'task_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'title' => $this->data['title'],
            ]),
        ])->onQueue('low');
    }

    /**
     * Warn the employeee by
     * * logging the fact to the Employee logs,
     * * creating a notification in the UI for the employee.
     */
    private function warnAboutTaskAssignment(): void
    {
        NotifyEmployee::dispatch([
            'employee_id' => $this->data['employee_id'],
            'action' => 'task_assigned',
            'objects' => json_encode([
                'author_id' => $this->author->id,
                'author_name' => $this->author->name,
                'title' => $this->data['title'],
            ]),
        ])->onQueue('low');
    }
}
