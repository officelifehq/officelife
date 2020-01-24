<?php

namespace App\Services\Company\Employee\Birthdate;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Carbon\Exceptions\InvalidDateException;

class SetBirthdate extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'year' => 'required|integer',
            'month' => 'required|integer',
            'day' => 'required|integer',
        ];
    }

    /**
     * Set the birthdate of an employee.
     */
    public function execute(array $data): Employee
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('officelife.authorizations.hr'),
            $data['employee_id']
        );

        $employee = $this->validateEmployeeBelongsToCompany($data);

        $carbonObject = $this->checkDateValidity($data);

        $employee = $this->save($employee, $carbonObject);

        $this->log($data, $author, $employee, $carbonObject);

        return $employee;
    }

    private function checkDateValidity(array $data): Carbon
    {
        try {
            $carbonObject = Carbon::createSafe($data['year'], $data['month'], $data['day']);
        } catch (InvalidDateException $e) {
            throw new \Exception(trans('app.error_invalid_date'));
        }

        return $carbonObject;
    }

    private function save(Employee $employee, Carbon $date): Employee
    {
        $employee->birthdate = $date;
        $employee->save();

        return $employee;
    }

    private function log(array $data, Employee $author, Employee $employee, Carbon $date): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_birthday_set',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'birthday' => $date->format('Y-m-d'),
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $data['employee_id'],
            'action' => 'birthday_set',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'birthday' => $date->format('Y-m-d'),
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');
    }
}
