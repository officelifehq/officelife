<?php

namespace App\Services\Company\Employee;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\EmployeeEvent;
use App\Models\Company\EmployeeImportantDate;

class SetImportantDateForEmployee extends BaseService
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
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'occasion' => 'required|string|max:255',
            'date' => 'required|date_format:Y-m-d',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Set an important date for an employee.
     * Important dates are dates like birthdate or hiring date.
     * For each one of these dates, we also add a kind of reminder, called
     * Employee Event.
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
            config('villagers.authorizations.hr'),
            $data['employee_id']
        );

        $employee = Employee::where('company_id', $data['company_id'])
            ->findOrFail($data['employee_id']);

        // transform the birthdate as a carbon object
        $carbonObject = Carbon::createFromFormat('Y-m-d', $data['date']);

        // save the birthdate
        $this->setImportantDate($employee, $data['occasion'], $carbonObject);

        // check to see if an employee event already exists about this occasion
        $event = $employee->employeeEvents()
            ->where('label', $data['occasion'])
            ->first();

        if (! is_null($event)) {
            $event->delete();
        }

        // calculate the next occurence of the date in order to create an event
        $dateOfEvent = DateHelper::getNextOccurence($carbonObject);

        EmployeeEvent::create([
            'company_id' => $data['company_id'],
            'employee_id' => $data['employee_id'],
            'label' => $data['occasion'],
            'date' => $dateOfEvent,
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_birthday_set',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'birthday' => $data['date'],
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
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'birthday' => $data['date'],
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        return $employee;
    }

    /**
     * Set the important date for the given occasion.
     *
     * @param Employee $employee
     * @param string $occasion
     * @param Carbon $date
     * @return EmployeeImportantDate
     */
    private function setImportantDate(Employee $employee, string $occasion, Carbon $date) : EmployeeImportantDate
    {
        $importantDate = $employee->importantDates()
            ->where('occasion', $occasion)
            ->first();

        if (is_null($importantDate)) {
            return EmployeeImportantDate::create([
                'employee_id' => $employee->id,
                'occasion' => $occasion,
                'date' => $date,
            ]);
        }

        $importantDate->date = $date;
        $importantDate->save();

        return $importantDate;
    }
}
