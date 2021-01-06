<?php

namespace App\Services\Company\Adminland\Employee;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Jobs\NotifyEmployee;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Services\User\Avatar\GenerateDefaultAvatar;

class AddEmployeeToCompany extends BaseService
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
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'email' => 'required|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'permission_level' => 'required|integer',
            'send_invitation' => 'required|boolean',
        ];
    }

    /**
     * Add someone to the company.
     *
     * @param array $data
     *
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->createEmployee($data);

        $this->addHolidays($data);

        $this->notifyEmployee($data);

        $this->logAccountAudit($data);

        if ($data['send_invitation']) {
            (new InviteEmployeeToBecomeUser)->execute([
                'company_id' => $data['company_id'],
                'author_id' => $this->author->id,
                'employee_id' => $this->employee->id,
            ]);
        }

        return $this->employee;
    }

    /**
     * Create the employee.
     *
     * @param array $data
     *
     * @return Employee
     */
    private function createEmployee(array $data): Employee
    {
        $uuid = Str::uuid()->toString();

        $avatar = (new GenerateDefaultAvatar)->execute([
            'name' => $data['first_name'].' '.$data['last_name'],
        ]);

        $this->employee = Employee::create([
            'company_id' => $data['company_id'],
            'uuid' => $uuid,
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'avatar' => $avatar,
            'permission_level' => $data['permission_level'],
        ]);

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'employee_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
            ]),
        ])->onQueue('low');

        return $this->employee;
    }

    /**
     * Add the default amount of holidays to this new employee.
     *
     * @param array $data
     */
    private function addHolidays(array $data): void
    {
        $company = Company::find($data['company_id']);

        $this->employee->amount_of_allowed_holidays = $company->getCurrentPTOPolicy()->default_amount_of_allowed_holidays;
        $this->employee->save();
    }

    /**
     * Add a welcome message for the employee.
     *
     * @param array $data
     */
    private function notifyEmployee(array $data): void
    {
        $company = Company::findOrFail($data['company_id']);

        NotifyEmployee::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'employee_added_to_company',
            'objects' => json_encode([
                'company_name' => $company->name,
            ]),
        ])->onQueue('low');
    }

    /**
     * Add an audit log entry for this action.
     *
     * @param array $data
     */
    private function logAccountAudit(array $data): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_added_to_company',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_email' => $data['email'],
                'employee_first_name' => $data['first_name'],
                'employee_last_name' => $data['last_name'],
                'employee_name' => $this->employee->name,
            ]),
        ])->onQueue('low');
    }
}
