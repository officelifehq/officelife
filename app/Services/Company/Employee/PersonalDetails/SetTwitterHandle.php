<?php

namespace App\Services\Company\Employee\PersonalDetails;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;

class SetTwitterHandle extends BaseService
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
            'twitter' => 'nullable|string|max:255',
        ];
    }

    /**
     * Set the twitter handle of an employee.
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

        $this->employee->twitter_handle = $this->valueOrNull($data, 'twitter');
        $this->employee->save();

        if ($this->valueOrNull($data, 'twitter')) {
            $this->log($data, false);
        } else {
            $this->log($data, true);
        }

        return $this->employee;
    }

    private function log(array $data, bool $resetTwitter): void
    {
        if (! $resetTwitter) {
            LogAccountAudit::dispatch([
                'company_id' => $data['company_id'],
                'action' => 'employee_twitter_set',
                'author_id' => $this->author->id,
                'author_name' => $this->author->name,
                'audited_at' => Carbon::now(),
                'objects' => json_encode([
                    'employee_id' => $this->employee->id,
                    'employee_name' => $this->employee->name,
                    'twitter' => $this->valueOrNull($data, 'twitter'),
                ]),
            ])->onQueue('low');

            LogEmployeeAudit::dispatch([
                'employee_id' => $data['employee_id'],
                'action' => 'twitter_set',
                'author_id' => $this->author->id,
                'author_name' => $this->author->name,
                'audited_at' => Carbon::now(),
                'objects' => json_encode([
                    'twitter' => $this->valueOrNull($data, 'twitter'),
                ]),
            ])->onQueue('low');
        }

        if ($resetTwitter) {
            LogAccountAudit::dispatch([
                'company_id' => $data['company_id'],
                'action' => 'employee_twitter_reset',
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
                'action' => 'twitter_reset',
                'author_id' => $this->author->id,
                'author_name' => $this->author->name,
                'audited_at' => Carbon::now(),
                'objects' => json_encode([]),
            ])->onQueue('low');
        }
    }
}
