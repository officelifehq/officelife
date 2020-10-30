<?php

namespace App\Services\Company\Employee\Worklog;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use App\Exceptions\WorklogAlreadyLoggedTodayException;

class LogWorklog extends BaseService
{
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
            'company_id' => 'required|integer|exists:companies,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'content' => 'required|string|max:65535',
            'date' => 'nullable|date_format:Y-m-d',
        ];
    }

    /**
     * Log the work that the employee has done.
     * Logging can only happen once per day.
     * Logging can only be done by the employee.
     *
     * @param array $data
     *
     * @return Worklog
     */
    public function execute(array $data): Worklog
    {
        $this->validateRules($data);

        $this->employee = $this->validateEmployeeBelongsToCompany($data);

        if ($this->employee->hasAlreadyLoggedWorklogToday()) {
            throw new WorklogAlreadyLoggedTodayException();
        }

        $this->resetWorklogMissed();

        $worklog = Worklog::create([
            'employee_id' => $data['employee_id'],
            'content' => $data['content'],
            'created_at' => $this->valueOrNow($data, 'date'),
        ]);

        $this->log($worklog, $data);

        return $worklog;
    }

    /**
     * Reset the counter indicating the number of missed daily worklog for the
     * given employee.
     */
    private function resetWorklogMissed(): void
    {
        $this->employee->consecutive_worklog_missed = 0;
        $this->employee->save();
    }

    private function log(Worklog $worklog, array $data): void
    {
        $author = Employee::findOrFail($data['author_id']);

        LogAccountAudit::dispatch([
            'company_id' => $this->employee->company_id,
            'action' => 'employee_worklog_logged',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
                'worklog_id' => $worklog->id,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'employee_worklog_logged',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
                'worklog_id' => $worklog->id,
            ]),
        ])->onQueue('low');
    }
}
