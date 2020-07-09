<?php

namespace App\Services\Company\Employee\Expense;

use Carbon\Carbon;
use App\Jobs\NotifyEmployee;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Expense;
use App\Models\Company\Employee;

class AcceptExpense extends BaseService
{
    private Expense $expense;

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
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Accept the expense if the employee has the right to do so.
     * An expense can be accepted either by the manager of the employee who has
     * created the expense, or by someone with HR or admin role.
     *
     * @param array $data
     * @return Expense
     */
    public function execute(array $data): Expense
    {
        $this->data = $data;

        $this->validate();

        $this->accept();

        $this->notifyEmployee();

        $this->notifyAccountingDepartment();

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
            ->canBypassPermissionLevelIfManager($this->data['employee_id'])
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($this->data);

        $this->expense = Expense::where('employee_id', $this->data['employee_id'])
            ->findOrFail($this->data['expense_id']);
    }

    /**
     * Accept the expense.
     */
    private function accept(): void
    {
        $this->expense->status = Expense::AWAITING_ACCOUTING_APPROVAL;
        $this->expense->manager_approver_approved_at = Carbon::now();
        $this->expense->save();
    }

    /**
     * Notify the employee that the expense has been accepted.
     */
    private function notifyEmployee(): void
    {
        NotifyEmployee::dispatch([
            'employee_id' => $this->data['employee_id'],
            'action' => 'task_assigned',
            'objects' => json_encode([
                'title' => $this->expense->title,
            ]),
            'is_dummy' => $this->valueOrFalse($this->data, 'is_dummy'),
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
