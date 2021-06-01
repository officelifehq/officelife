<?php

namespace App\Services\Company\Adminland\Company;

use Carbon\Carbon;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Exceptions\UserAlreadyInvitedException;

class JoinCompany extends BaseService
{
    private array $data;
    private Company $company;
    private User $user;
    private Employee $employee;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'code' => 'required|string|max:255|exists:companies,code_to_join_company',
        ];
    }

    /**
     * Let a user join an existing company with an invitation code.
     *
     * @param array $data
     * @return Company
     */
    public function execute(array $data): Company
    {
        $this->data = $data;
        $this->validate();
        $this->findCompany();
        $this->addUser();
        $this->logAccountAudit();

        return $this->company;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::find($this->data['user_id']);

        // check that the user is not already part of the company
        $employee = Employee::where('user_id', $this->user->id)->first();
        if ($employee) {
            throw new UserAlreadyInvitedException();
        }
    }

    private function findCompany(): void
    {
        $this->company = Company::where('code_to_join_company', $this->data['code'])
            ->firstOrFail();
    }

    private function addUser(): void
    {
        $this->employee = Employee::create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
            'email' => $this->user->email,
            'uuid' => Str::uuid()->toString(),
            'permission_level' => config('officelife.permission_level.user'),
        ]);
    }

    private function logAccountAudit(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->company->id,
            'action' => 'employee_joined_company',
            'author_id' => $this->employee->id,
            'author_name' => $this->employee->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'company_name' => $this->company->name,
            ]),
        ])->onQueue('low');
    }
}
