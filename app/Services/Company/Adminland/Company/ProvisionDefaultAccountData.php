<?php

namespace App\Services\Company\Adminland\Company;

use Carbon\Carbon;
use App\Services\BaseService;
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

        // PTO policies
        $currentYear = Carbon::now();
        for ($i = 1; $i <= 5; $i++) {
            (new CreateCompanyPTOPolicy)->execute([
                'company_id' => $data['company_id'],
                'author_id' => $data['author_id'],
                'year' => $currentYear->format('Y'),
                'default_amount_of_allowed_holidays' => 30,
                'default_amount_of_sick_days' => 5,
                'default_amount_of_pto_days' => 5,
            ]);
            $currentYear->addYear();
        }
    }
}
