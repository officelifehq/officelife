<?php

namespace App\Services\Company\Employee\Birthday;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\EmployeeEvent;

class SetBirthdayForEmployee extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:users,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'date' => 'required|date|date_format:Y-m-d',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Set the birthdate of an employee.
     * This also sets an Employee Event that will be used to trigger all the
     * flows associated with this event.
     *
     * @param array $data
     * @return Employee
     */
    public function execute(array $data) : Employee
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr'),
            $data['employee_id']
        );

        $employee = Employee::where('company_id', $data['company_id'])
            ->findOrFail($data['employee_id']);

        // save the birthdate
        $employee->birthdate = $data['date'];
        $employee->save();

        // transform the birthdate as a carbon object
        $carbonObject = Carbon::createFromFormat('Y-m-d', $data['date']);
        $dateOfEvent = DateHelper::getNextOccurence($carbonObject);

        EmployeeEvent::create([
            'company_id' => $data['company_id'],
            'employee_id' => $data['employee_id'],
            'label' => 'birthday',
            'date' => $dateOfEvent,
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_birthday_set',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'birthday' => $data['date'],
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        LogEmployeeAudit::dispatch([
            'company_id' => $data['company_id'],
            'employee_id' => $data['employee_id'],
            'action' => 'birthday_set',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'birthday' => $data['date'],
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $employee;
    }
}
