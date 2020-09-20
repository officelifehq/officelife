<?php

namespace App\Services\Company\Adminland\Company;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Company;
use App\Models\Company\Employee;

class AddUserToCompany extends BaseService
{
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
            'user_id' => 'required|integer|exists:users,id',
            'permission_level' => 'required|integer',
        ];
    }

    /**
     * Add a user to the company.
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

        $employee = Employee::create([
            'user_id' => $data['user_id'],
            'company_id' => $data['company_id'],
            'uuid' => Str::uuid()->toString(),
            'permission_level' => $data['permission_level'],
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'user_added_to_company',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'user_id' => $employee->user->id,
                'user_email' => $employee->user->email,
            ]),
        ])->onQueue('low');

        return $employee;
    }
}
