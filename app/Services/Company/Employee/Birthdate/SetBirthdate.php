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
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfEmployee($data['employee_id'])
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($data);

        $carbonObject = $this->checkDateValidity($data);

        $this->save($carbonObject);

        $this->log($data, $carbonObject);

        return $this->employee;
    }

    private function checkDateValidity(array $data): Carbon
    {
        try {
            $carbonObject = Carbon::createSafe($data['year'], $data['month'], $data['day']);
            if ($carbonObject === false) {
                throw new InvalidDateException('', '');
            }
        } catch (InvalidDateException $e) {
            throw new \Exception(trans('app.error_invalid_date'));
        }

        return $carbonObject;
    }

    private function save(Carbon $date): void
    {
        $this->employee->birthdate = $date;
        $this->employee->save();
    }

    private function log(array $data, Carbon $date): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_birthday_set',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
                'birthday' => $date->format('Y-m-d'),
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $data['employee_id'],
            'action' => 'birthday_set',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'birthday' => $date->format('Y-m-d'),
            ]),
        ])->onQueue('low');
    }
}
