<?php

namespace App\Services\Company\Adminland\CompanyPTOPolicy;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Company;
use App\Models\Company\CompanyPTOPolicy;

class UpdateCompanyPTOPolicy extends BaseService
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
            'company_pto_policy_id' => 'required|integer|exists:company_pto_policies,id',
            'total_worked_days' => 'required|integer',
            'default_amount_of_allowed_holidays' => 'nullable|integer',
            'default_amount_of_sick_days' => 'nullable|integer',
            'default_amount_of_pto_days' => 'nullable|integer',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Update a company PTO policy.
     *
     * @param array $data
     * @return CompanyPTOPolicy
     */
    public function execute(array $data) : CompanyPTOPolicy
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr')
        );

        $ptoPolicy = CompanyPTOPolicy::where('company_id', $data['company_id'])
            ->findOrFail($data['company_pto_policy_id']);

        $ptoPolicy->total_worked_days = $data['total_worked_days'];
        $ptoPolicy->default_amount_of_allowed_holidays = $this->nullOrValue($data, 'default_amount_of_allowed_holidays');
        $ptoPolicy->default_amount_of_sick_days = $this->nullOrValue($data, 'default_amount_of_sick_days');
        $ptoPolicy->default_amount_of_pto_days = $this->nullOrValue($data, 'default_amount_of_pto_days');
        $ptoPolicy->save();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'company_pto_policy_updated',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'company_pto_policy_id' => $ptoPolicy->id,
                'company_pto_policy_year' => $ptoPolicy->year,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        $ptoPolicy->refresh();

        return $ptoPolicy;
    }
}
