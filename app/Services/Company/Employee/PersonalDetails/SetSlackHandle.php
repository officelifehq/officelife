<?php

namespace App\Services\Company\Employee\PersonalDetails;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;

class SetSlackHandle extends BaseService
{
    protected Employee $employee;

    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'slack' => 'nullable|string|max:255',
        ];
    }

    /**
     * Set the slack handle of an employee.
     */
    public function execute(array $data): Employee
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfEmployee($data['employee_id'])
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($data);

        $this->employee->slack_handle = $this->valueOrNull($data, 'slack');
        $this->employee->save();

        if ($this->valueOrNull($data, 'slack')) {
            $this->log($data, false);
        } else {
            $this->log($data, true);
        }

        return $this->employee;
    }

    private function log(array $data, bool $resetSlack): void
    {
        if (! $resetSlack) {
            LogAccountAudit::dispatch([
                'company_id' => $data['company_id'],
                'action' => 'employee_slack_set',
                'author_id' => $this->author->id,
                'author_name' => $this->author->name,
                'audited_at' => Carbon::now(),
                'objects' => json_encode([
                    'employee_id' => $this->employee->id,
                    'employee_name' => $this->employee->name,
                    'slack' => $this->valueOrNull($data, 'slack'),
                ]),
            ])->onQueue('low');

            LogEmployeeAudit::dispatch([
                'employee_id' => $data['employee_id'],
                'action' => 'slack_set',
                'author_id' => $this->author->id,
                'author_name' => $this->author->name,
                'audited_at' => Carbon::now(),
                'objects' => json_encode([
                    'slack' => $this->valueOrNull($data, 'slack'),
                ]),
            ])->onQueue('low');
        }

        if ($resetSlack) {
            LogAccountAudit::dispatch([
                'company_id' => $data['company_id'],
                'action' => 'employee_slack_reset',
                'author_id' => $this->author->id,
                'author_name' => $this->author->name,
                'audited_at' => Carbon::now(),
                'objects' => json_encode([
                    'employee_id' => $this->employee->id,
                    'employee_name' => $this->employee->name,
                ]),
            ])->onQueue('low');

            LogEmployeeAudit::dispatch([
                'employee_id' => $data['employee_id'],
                'action' => 'slack_reset',
                'author_id' => $this->author->id,
                'author_name' => $this->author->name,
                'audited_at' => Carbon::now(),
                'objects' => json_encode([]),
            ])->onQueue('low');
        }
    }
}
