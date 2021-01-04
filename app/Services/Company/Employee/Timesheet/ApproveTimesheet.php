<?php

namespace App\Services\Company\Employee\Timesheet;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;

class ApproveTimesheet extends BaseService
{
    private array $data;

    private Employee $employee;

    private Timesheet $timesheet;

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
            'timesheet_id' => 'required|integer|exists:timesheets,id',
        ];
    }

    /**
     * Accept a timesheet for validation.
     * Only managers and HR or above can accept a timesheet.
     *
     * @param array $data
     * @return Timesheet
     */
    public function execute(array $data): Timesheet
    {
        $this->data = $data;
        $this->validate();
        $this->accept();
        $this->log();

        return $this->timesheet;
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

        $this->timesheet = Timesheet::where('company_id', $this->data['company_id'])
            ->where('employee_id', $this->employee->id)
            ->findOrFail($this->data['timesheet_id']);
    }

    private function accept(): void
    {
        $this->timesheet->status = Timesheet::APPROVED;
        $this->timesheet->approved_at = Carbon::now();
        $this->timesheet->approver_id = $this->author->id;
        $this->timesheet->approver_name = $this->author->name;
        $this->timesheet->save();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'timesheet_approved',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'timesheet_id' => $this->timesheet->id,
                'started_at' => DateHelper::formatDate($this->timesheet->started_at),
                'ended_at' => DateHelper::formatDate($this->timesheet->ended_at),
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'timesheet_approved',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'timesheet_id' => $this->timesheet->id,
                'started_at' => DateHelper::formatDate($this->timesheet->started_at),
                'ended_at' => DateHelper::formatDate($this->timesheet->ended_at),
            ]),
        ])->onQueue('low');
    }
}
