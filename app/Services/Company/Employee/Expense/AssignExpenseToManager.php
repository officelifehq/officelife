<?php

namespace App\Services\Company\Employee\Expense;

use Carbon\Carbon;
use App\Jobs\NotifyEmployee;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use App\Services\Company\Task\CreateTask;

class AssignExpenseToManager extends BaseService
{
    private Expense $expense;

    private Employee $manager;

    private Employee $employee;

    private array $data;

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
            'expense_id' => 'required|integer|exists:expenses,id',
            'manager_id' => 'required|integer|exists:employees,id',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Assign an expense to the employee's manager.
     *
     * @param array $data
     * @return Expense
     */
    public function execute(array $data): Expense
    {
        $this->data = $data;

        $this->validate();

        $this->assign();

        $this->createTask();

        $this->createNotification();

        $this->log();

        return $this->expense;
    }

    /**
     * Make preliminary checks.
     */
    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfEmployee($this->data['employee_id'])
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($this->data);

        $this->manager = Employee::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['manager_id']);

        $this->expense = Expense::where('employee_id', $this->data['employee_id'])
            ->findOrFail($this->data['expense_id']);
    }

    /**
     * Assign the expense to the manager.
     */
    private function assign(): void
    {
        $this->expense->manager_approver_id = $this->manager->id;
        $this->expense->manager_approver_name = $this->manager->name;
        $this->expense->save();
    }

    /**
     * Create a task for the manager.
     */
    private function createTask(): void
    {
        $request = [
            'company_id' => $this->author->company_id,
            'author_id' => $this->author->id,
            'employee_id' => $this->manager->id,
            'title' => trans('employee.expense_task_associated', [
                'name' => $this->employee->name,
            ]),
            'is_dummy' => $this->valueOrFalse($this->data, 'is_dummy'),
        ];

        (new CreateTask)->execute($request);
    }

    /**
     * Create a task for the manager.
     */
    private function createNotification(): void
    {
        NotifyEmployee::dispatch([
            'employee_id' => $this->manager->id,
            'action' => 'expense_assigned_for_validation',
            'objects' => json_encode([
                'name' => $this->employee->name,
            ]),
        ])->onQueue('low');
    }

    /**
     * Add audit logs.
     */
    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'expense_assigned_to_manager',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'manager_id' => $this->manager->id,
                'manager_name' => $this->manager->name,
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
                'expense_id' => $this->expense->id,
                'expense_title' => $this->expense->title,
                'expense_amount' => $this->expense->amount,
                'expense_currency' => $this->expense->currency,
                'expensed_at' => $this->expense->expensed_at,
            ]),
            'is_dummy' => $this->valueOrFalse($this->data, 'is_dummy'),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->manager->id,
            'action' => 'expense_assigned',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
                'expense_id' => $this->expense->id,
                'expense_title' => $this->expense->title,
                'expense_amount' => $this->expense->amount,
                'expense_currency' => $this->expense->currency,
                'expensed_at' => $this->expense->expensed_at,
            ]),
            'is_dummy' => $this->valueOrFalse($this->data, 'is_dummy'),
        ])->onQueue('low');
    }
}
