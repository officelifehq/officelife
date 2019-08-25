<?php

namespace App\Services\Company\Adminland\Employee;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Services\User\Avatar\GenerateAvatar;

class AddEmployeeToCompany extends BaseService
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
            'email' => 'required|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'permission_level' => 'required|integer',
            'send_invitation' => 'required|boolean',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Add someone to the company.
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
            config('homas.authorizations.hr')
        );

        $employee = $this->createEmployee($data, $author);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_added_to_company',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $employee->id,
                'employee_email' => $data['email'],
                'employee_first_name' => $data['first_name'],
                'employee_last_name' => $data['last_name'],
                'employee_name' => $employee->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        if ($data['send_invitation']) {
            (new InviteUser)->execute([
                'company_id' => $data['company_id'],
                'author_id' => $author->id,
                'employee_id' => $employee->id,
            ]);
        }

        return $employee;
    }

    /**
     * Create the employee.
     *
     * @param array $data
     * @param Employee $author
     * @return Employee
     */
    private function createEmployee(array $data, Employee $author) : Employee
    {
        $uuid = Str::uuid()->toString();

        $avatar = (new GenerateAvatar)->execute([
            'uuid' => $uuid,
            'size' => 200,
        ]);

        $employee = Employee::create([
            'company_id' => $data['company_id'],
            'uuid' => $uuid,
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'avatar' => $avatar,
            'permission_level' => $data['permission_level'],
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        LogEmployeeAudit::dispatch([
            'employee_id' => $employee->id,
            'action' => 'employee_created',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        return $employee;
    }
}
