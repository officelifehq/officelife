<?php

namespace App\Services\Company\Employee\Holiday;

use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\EmployeePlannedHoliday;
use App\Services\BaseService;
use Carbon\Carbon;

class DestroyTimeOff extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:employees,id',
            'company_id' => 'required|integer|exists:companies,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'employee_planned_holiday_id' => 'required|integer|exists:employee_planned_holidays,id',
        ];
    }

    /**
     * Destroy a planned holiday.
     */
    public function execute(array $data): bool
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfEmployee($data['employee_id'])
            ->canExecuteService();

        $holiday = EmployeePlannedHoliday::findOrFail($data['employee_planned_holiday_id']);
        $holiday->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'time_off_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'planned_holiday_date' => $holiday->planned_date,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $holiday->employee_id,
            'action' => 'time_off_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'planned_holiday_date' => $holiday->planned_date,
            ]),
        ])->onQueue('low');

        return true;
    }
}
