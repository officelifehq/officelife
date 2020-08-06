<?php

namespace App\Services\Company\Employee\Expense;

use Carbon\Carbon;
use ErrorException;
use App\Jobs\NotifyEmployee;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Expense;
use App\Exceptions\NotEnoughPermissionException;

class RejectExpenseAsManager extends BaseService
{
    private Expense $expense;

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
            'expense_id' => 'required|integer|exists:expenses,id',
            'reason' => 'required|string|max:65535',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Reject the expense as the manager associated with the expense.
     *
     * @param array $data
     * @return Expense
     */
    public function execute(array $data): Expense
    {
        $this->data = $data;

        $this->validate();

        $this->reject();

        $this->notifyEmployee();

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
            ->asNormalUser()
            ->canExecuteService();

        $this->expense = Expense::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['expense_id']);

        if ($this->expense->status != Expense::AWAITING_MANAGER_APPROVAL) {
            throw new ErrorException();
        }

        // ugly but necessary as an employee can be deleted before the expense
        // is processed
        if ($this->expense->employee) {
            if (! $this->author->isManagerOf($this->expense->employee->id)) {
                throw new NotEnoughPermissionException();
            }
        }
    }

    /**
     * Reject the expense.
     */
    private function reject(): void
    {
        $this->expense->status = Expense::REJECTED_BY_MANAGER;
        $this->expense->manager_approver_id = $this->author->id;
        $this->expense->manager_approver_name = $this->author->name;
        $this->expense->manager_approver_approved_at = Carbon::now();
        $this->expense->manager_rejection_explanation = $this->data['reason'];
        $this->expense->save();
    }

    /**
     * Notify the employee that the expense has been rejected.
     */
    private function notifyEmployee()
    {
        if (! $this->expense->employee) {
            return;
        }

        NotifyEmployee::dispatch([
            'employee_id' => $this->expense->employee_id,
            'action' => 'expense_rejected_by_manager',
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
            'action' => 'expense_rejected_by_manager',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->expense->employee_id,
                'employee_name' => $this->expense->employee_name,
                'expense_id' => $this->expense->id,
                'expense_title' => $this->expense->title,
                'expense_amount' => $this->expense->amount,
                'expense_currency' => $this->expense->currency,
                'expensed_at' => $this->expense->expensed_at,
            ]),
            'is_dummy' => $this->valueOrFalse($this->data, 'is_dummy'),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->author->id,
            'action' => 'expense_rejected_for_employee',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->expense->employee_id,
                'employee_name' => $this->expense->employee_name,
                'expense_id' => $this->expense->id,
                'expense_title' => $this->expense->title,
                'expense_amount' => $this->expense->amount,
                'expense_currency' => $this->expense->currency,
                'expensed_at' => $this->expense->expensed_at,
            ]),
            'is_dummy' => $this->valueOrFalse($this->data, 'is_dummy'),
        ])->onQueue('low');

        if ($this->expense->employee) {
            LogEmployeeAudit::dispatch([
                'employee_id' => $this->expense->employee_id,
                'action' => 'expense_rejected_by_manager',
                'author_id' => $this->author->id,
                'author_name' => $this->author->name,
                'audited_at' => Carbon::now(),
                'objects' => json_encode([
                    'expense_id' => $this->expense->id,
                    'expense_title' => $this->expense->title,
                    'expense_amount' => $this->expense->amount,
                    'expense_currency' => $this->expense->currency,
                    'expensed_at' => $this->expense->expensed_at,
                    'manager_name' => $this->author->name,
                ]),
                'is_dummy' => $this->valueOrFalse($this->data, 'is_dummy'),
            ])->onQueue('low');
        }
    }
}
