<?php

namespace App\Services\Company\Adminland\Company;

use Carbon\Carbon;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Services\Company\Adminland\CompanyPTOPolicy\CreateCompanyPTOPolicy;

class ProvisionDefaultAccountData extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:employees,id',
            'company_id' => 'required|integer|exists:companies,id',
        ];
    }

    /**
     * Populate the account with default data.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->validateRules($data);

        $employee = Employee::find($data['author_id']);
        $company = $employee->company;

        // PTO policies
        $currentYear = Carbon::now();
        for ($i = 1; $i <= 5; $i++) {
            (new CreateCompanyPTOPolicy)->execute([
                'company_id' => $company->id,
                'author_id' => $employee->id,
                'year' => $currentYear->format('Y'),
                'default_amount_of_allowed_holidays' => 30,
                'default_amount_of_sick_days' => 5,
                'default_amount_of_pto_days' => 5,
            ]);
            $currentYear->addYear();
        }

        // add holidays for the newly created employee
        $employee->amount_of_allowed_holidays = $company->getCurrentPTOPolicy()->default_amount_of_allowed_holidays;
        $employee->save();
    }
}
