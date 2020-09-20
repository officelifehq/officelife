<?php

namespace App\Services\Company\Employee\WorkFromHome;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\WorkFromHome;

class UpdateWorkFromHomeInformation extends BaseService
{
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
            'date' => 'required|date_format:Y-m-d',
            'work_from_home' => 'required|boolean',
        ];
    }

    /**
     * Update the information about working from home for the given employee.
     *
     * @param array $data
     *
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->validateRules($data);

        $employee = $this->validateEmployeeBelongsToCompany($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfEmployee($data['employee_id'])
            ->canExecuteService();

        // check to see if there is already an entry for this date
        $date = Carbon::createFromFormat('Y-m-d', $data['date'])->format('Y-m-d 00:00:00');
        $entry = WorkFromHome::where('date', $date)
            ->where('employee_id', $data['employee_id'])
            ->first();

        // if entry exists and the data indicates that the employee is not
        // working from home, we need to delete the entry.
        if ($entry && $data['work_from_home'] == false) {
            $entry->delete();
            $this->logEntryDestroyed($data, $employee);
        }

        // if entry doesn't exist and the boolean is true, we need to create the
        // entry in the database
        if (! $entry && $data['work_from_home'] == true) {
            WorkFromHome::create([
                'employee_id' => $data['employee_id'],
                'date' => $data['date'],
                'work_from_home' => true,
            ]);

            $this->logEntryCreated($data, $employee);
        }

        return $employee;
    }

    private function logEntryCreated(array $data, Employee $employee): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $employee->company_id,
            'action' => 'employee_work_from_home_logged',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'date' => $data['date'],
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $employee->id,
            'action' => 'work_from_home_logged',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'date' => $data['date'],
            ]),
        ])->onQueue('low');
    }

    private function logEntryDestroyed(array $data, Employee $employee): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $employee->company_id,
            'action' => 'employee_work_from_home_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'date' => $data['date'],
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $employee->id,
            'action' => 'work_from_home_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'date' => $data['date'],
            ]),
        ])->onQueue('low');
    }
}
