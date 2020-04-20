<?php

namespace App\Services\Company\Adminland\Company;

use Carbon\Carbon;
use App\Models\User\User;
use Illuminate\Support\Str;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Services\User\Avatar\GenerateAvatar;

class CreateCompany extends BaseService
{
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
        $this->validateRules($data);

        $company = Company::create([
            'name' => $data['name'],
        ]);

        $user = User::find($data['author_id']);
        $employee = $this->addFirstEmployee($company, $user);
        $this->provisionDefaultAccountData($company, $employee);

        // add holidays for the newly created employee
        $employee->amount_of_allowed_holidays = $company->getCurrentPTOPolicy()->default_amount_of_allowed_holidays;
        $employee->save();

        $this->logAccountAudit($company, $employee);

        return $company;
    }

    /**
     * Add the first employee to the company.
     *
     * @param Company $company
     * @param User    $user
     *
     * @return Employee
     */
    private function addFirstEmployee(Company $company, User $user): Employee
    {
        $uuid = Str::uuid()->toString();

        $avatar = (new GenerateAvatar)->execute([
            'uuid' => $uuid,
            'size' => 200,
        ]);

        return Employee::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'uuid' => Str::uuid()->toString(),
            'permission_level' => config('officelife.permission_level.administrator'),
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'avatar' => $avatar,
        ]);
    }

    /**
     * Provision the newly created account with default data.
     *
     * @param Company  $company
     * @param Employee $employee
     *
     */
    private function provisionDefaultAccountData(Company $company, Employee $employee): void
    {
        (new ProvisionDefaultAccountData)->execute([
            'company_id' => $company->id,
            'author_id' => $employee->id,
        ]);
    }

    /**
     * Add an audit log entry for this action.
     *
     * @param Company  $company
     * @param Employee $employee
     *
     */
    private function logAccountAudit(Company $company, Employee $employee): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $company->id,
            'action' => 'account_created',
            'author_id' => $employee->id,
            'author_name' => $employee->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'company_name' => $company->name,
            ]),
        ])->onQueue('low');
    }
}
