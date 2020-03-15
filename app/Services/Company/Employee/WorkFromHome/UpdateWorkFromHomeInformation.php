<?php

namespace App\Services\Company\Employee\Morale;

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
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Update the information about working from home for the given employee.
     *
     * @param array $data
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->validate($data);

        $employee = $this->validateEmployeeBelongsToCompany($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $employee->company_id,
            config('officelife.authorizations.hr'),
            $data['employee_id']
        );

        // check to see if there is already an entry for this date
        $entry = WorkFromHome::where('date', $data['date'])
            ->where('employee_id', $data['employee_id'])
            ->first();

        // if entry exists and the data indicates that the employee is not
        // working from home, we need to delete the entry.
        if ($entry && $data['work_from_home'] == false) {
            $entry->delete();
        }

        if (!$entry && $data['work_from_home'] == true) {
            $entry = WorkFromHome::create([
                'employee_id' => $data['employee_id'],
                'date' => $data['date'],
                'work_from_home' => true,
            ]);

            $this->log($data, $employee, $author);
        }

        return $employee;
    }

    private function log(array $data, Employee $employee, Employee $author): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $employee->company_id,
            'action' => 'employee_work_from_home_logged',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'date' => $data['date'],
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $employee->id,
            'action' => 'work_from_home_logged',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'date' => $data['date'],
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');
    }
}
