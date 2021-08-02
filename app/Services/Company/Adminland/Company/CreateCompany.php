<?php

namespace App\Services\Company\Adminland\Company;

use Carbon\Carbon;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Jobs\ProvisionDefaultAccountData;

class CreateCompany extends BaseService
{
    private array $data;
    private Company $company;
    private Employee $employee;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:users,id',
            'name' => 'required|unique:companies,name|string|max:255',
        ];
    }

    /**
     * Create a company.
     *
     * @param array $data
     *
     * @return Company
     */
    public function execute(array $data): Company
    {
        $this->data = $data;
        $this->validate();
        $this->createCompany();
        $this->createUniqueInvitationCodeForCompany();
        $this->addFirstEmployee();
        $this->provisionDefaultAccountData();
        $this->generateSlug();
        $this->logAccountAudit();

        return $this->company;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);
    }

    private function createCompany(): void
    {
        $this->company = Company::create([
            'name' => $this->data['name'],
        ]);
    }

    private function createUniqueInvitationCodeForCompany(): void
    {
        $uniqueCode = uniqid();

        $company = Company::where('code_to_join_company', $uniqueCode)
            ->first();

        while ($company) {
            $this->createUniqueInvitationCodeForCompany();
        }

        $this->company->code_to_join_company = $uniqueCode;
        $this->company->save();
    }

    /**
     * Add the first employee to the company.
     */
    private function addFirstEmployee(): void
    {
        $user = User::find($this->data['author_id']);

        $this->employee = Employee::create([
            'user_id' => $user->id,
            'company_id' => $this->company->id,
            'uuid' => Str::uuid()->toString(),
            'permission_level' => config('officelife.permission_level.administrator'),
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'display_welcome_message' => true,
        ]);
    }

    /**
     * Provision the newly created account with default data.
     */
    private function provisionDefaultAccountData(): void
    {
        ProvisionDefaultAccountData::dispatch($this->employee);
    }

    private function generateSlug(): void
    {
        (new UpdateCompanySlug)->execute([
            'company_id' => $this->company->id,
        ]);
    }

    private function logAccountAudit(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->company->id,
            'action' => 'account_created',
            'author_id' => $this->employee->id,
            'author_name' => $this->employee->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'company_name' => $this->company->name,
            ]),
        ])->onQueue('low');
    }
}
