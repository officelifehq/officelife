<?php

namespace App\Services\Company\Adminland\Employee;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Services\Company\Adminland\Expense\DisallowEmployeeToManageExpenses;
use App\Jobs\CheckIfPendingExpenseShouldBeMovedToAccountingWhenManagerChanges;

class LockEmployee extends BaseService
{
    private array $data;
    private Employee $employee;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|exists:employees,id|integer',
            'company_id' => 'required|exists:companies,id|integer',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Lock an employee's account.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->data = $data;

        $this->employee = $this->validateEmployeeBelongsToCompany($data);

        $this->employee->locked = true;
        $this->employee->save();

        $this->removeAccountantRole();

        $this->changePotentialExpensesStatuses();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_locked',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_name' => $this->employee->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $data['employee_id'],
            'action' => 'employee_locked',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');
    }

    /**
     * Remove the accountant role, if it was set.
     */
    private function removeAccountantRole(): void
    {
        $request = [
            'company_id' => $this->data['company_id'],
            'author_id' => $this->author->id,
            'employee_id' => $this->employee->id,
        ];

        if ($this->employee->can_manage_expenses) {
            (new DisallowEmployeeToManageExpenses)->execute($request);
        }
    }

    private function changePotentialExpensesStatuses(): void
    {
        CheckIfPendingExpenseShouldBeMovedToAccountingWhenManagerChanges::dispatch($this->employee->company)
            ->onQueue('low');
    }
}
