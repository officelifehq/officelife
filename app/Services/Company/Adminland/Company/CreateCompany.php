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
     * @return Company
     */
    public function execute(array $data): Company
    {
        $this->validate($data);

        $company = Company::create([
            'name' => $data['name'],
        ]);

        $author = User::find($data['author_id']);

        $employee = $this->addFirstEmployee($company, $author);

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

        (new ProvisionDefaultAccountData)->execute([
            'company_id' => $company->id,
            'author_id' => $employee->id,
        ]);

        // add holidays for the newly created employee
        $employee->amount_of_allowed_holidays = $company->getCurrentPTOPolicy()->default_amount_of_allowed_holidays;
        $employee->save();

        return $company;
    }

    /**
     * Add the first employee to the company.
     *
     * @param Company $company
     * @param User $author
     * @return Employee
     */
    private function addFirstEmployee(Company $company, User $author): Employee
    {
        $uuid = Str::uuid()->toString();

        $avatar = (new GenerateAvatar)->execute([
            'uuid' => $uuid,
            'size' => 200,
        ]);

        return Employee::create([
            'user_id' => $author->id,
            'company_id' => $company->id,
            'uuid' => Str::uuid()->toString(),
            'permission_level' => config('officelife.authorizations.administrator'),
            'email' => $author->email,
            'first_name' => $author->first_name,
            'last_name' => $author->last_name,
            'avatar' => $avatar,
        ]);
    }
}
