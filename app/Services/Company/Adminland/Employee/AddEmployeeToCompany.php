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
use App\Services\QueuableService;
use App\Services\DispatchableService;
use App\Exceptions\EmailAlreadyUsedException;

class AddEmployeeToCompany extends BaseService implements QueuableService
{
    use DispatchableService;

    private Employee $employee;

    private array $data;

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
        $this->data = $data;
        $this->handle();

        return $this->employee;
    }

    /**
     * Add someone to the company.
     */
    public function handle(): void
    {
        $this->validate();

        $this->makeSureEmailIsUniqueInCompany();
        $this->createEmployee();
        $this->addHolidays();

        $this->notifyEmployee();

        $this->logAccountAudit();

        if ($this->data['send_invitation']) {
            (new InviteEmployeeToBecomeUser)->execute([
                'company_id' => $this->data['company_id'],
                'author_id' => $this->author->id,
                'employee_id' => $this->employee->id,
            ]);
        }
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();
    }

    private function makeSureEmailIsUniqueInCompany(): void
    {
        $employee = Employee::where('company_id', $this->data['company_id'])
            ->where('email', $this->data['email'])
            ->first();

        if ($employee) {
            throw new EmailAlreadyUsedException();
        }
    }

    private function createEmployee(): Employee
    {
        $uuid = Str::uuid()->toString();

        $this->employee = Employee::create([
            'company_id' => $this->data['company_id'],
            'uuid' => $uuid,
            'email' => $this->data['email'],
            'first_name' => $this->data['first_name'],
            'last_name' => $this->data['last_name'],
            'permission_level' => $this->data['permission_level'],
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
     */
    private function addHolidays(): void
    {
        $company = Company::find($this->data['company_id']);

        Employee::where('id', $this->employee->id)->update([
            'amount_of_allowed_holidays' => $company->getCurrentPTOPolicy()->default_amount_of_allowed_holidays,
        ]);
    }

    /**
     * Add a welcome message for the employee.
     */
    private function notifyEmployee(): void
    {
        $company = Company::findOrFail($this->data['company_id']);

        NotifyEmployee::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'employee_added_to_company',
            'objects' => json_encode([
                'company_name' => $company->name,
            ]),
        ])->onQueue('low');
    }

    private function logAccountAudit(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'employee_added_to_company',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_email' => $this->data['email'],
                'employee_first_name' => $this->data['first_name'],
                'employee_last_name' => $this->data['last_name'],
                'employee_name' => $this->employee->name,
            ]),
        ])->onQueue('low');
    }
}
