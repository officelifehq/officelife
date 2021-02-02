<?php

namespace App\Services\Company\Employee\ConsultantRate;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\ConsultantRate;

class DestroyConsultantRate extends BaseService
{
    protected Employee $employee;

    protected array $data;

    protected ConsultantRate $rate;

    protected int $formerRate;

    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'rate_id' => 'required|integer|exists:consultant_rates,id',
        ];
    }

    /**
     * Destroy the given consultant rate.
     */
    public function execute(array $data): void
    {
        $this->data = $data;

        $this->validate();
        $this->destroy();
        $this->log();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfManager($this->data['author_id'], $this->data['employee_id'])
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($this->data);

        $this->rate = ConsultantRate::where('company_id', $this->data['company_id'])
            ->where('employee_id', $this->data['employee_id'])
            ->findOrFail($this->data['rate_id']);
    }

    private function destroy(): void
    {
        $this->formerRate = $this->rate->rate;
        $this->rate->delete();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'consultant_rate_destroy',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
                'rate' => $this->formerRate,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->data['employee_id'],
            'action' => 'consultant_rate_destroy',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'rate' => $this->formerRate,
            ]),
        ])->onQueue('low');
    }
}
