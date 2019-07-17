<?php

namespace App\Services\Company\Employee\Worklog;

use App\Services\BaseService;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use App\Services\Company\Employee\LogEmployeeAction;
use App\Exceptions\WorklogAlreadyLoggedTodayException;
use App\Services\Company\Adminland\Company\LogAuditAction;

class LogWorklog extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'author_id' => 'required|integer|exists:users,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'content' => 'required|string|max:65535',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Log the work that the employee has done.
     * Logging can only happen once per day.
     * Logging can only be done by the employee, so there is no author_id field
     * with this service.
     *
     * @param array $data
     * @return Worklog
     */
    public function execute(array $data) : Worklog
    {
        $this->validate($data);

        $employee = Employee::findOrFail($data['employee_id']);

        $author = $this->validatePermissions(
            $data['author_id'],
            $employee->company_id,
            config('homas.authorizations.user'),
            $data['employee_id']
        );

        if ($employee->hasAlreadyLoggedWorklogToday()) {
            throw new WorklogAlreadyLoggedTodayException();
        }

        $this->resetWorklogMissed($employee);

        $Worklog = Worklog::create([
            'employee_id' => $data['employee_id'],
            'content' => $data['content'],
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        (new LogAuditAction)->execute([
            'company_id' => $employee->company_id,
            'action' => 'employee_worklog_logged',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'Worklog_id' => $Worklog->id,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        (new LogEmployeeAction)->execute([
            'company_id' => $employee->company_id,
            'employee_id' => $data['employee_id'],
            'action' => 'employee_worklog_logged',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'Worklog_id' => $Worklog->id,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $Worklog;
    }

    /**
     * Reset the counter indicating the number of missed daily worklog for the
     * given employee.
     *
     * @param Employee $employee
     * @return void
     */
    private function resetWorklogMissed(Employee $employee)
    {
        $employee->consecutive_worklog_missed = 0;
        $employee->save();
    }
}
