<?php

namespace App\Services\Company\Adminland\Company;

use Carbon\Carbon;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Services\Company\Adminland\Position\CreatePosition;
use App\Services\Company\Adminland\EmployeeStatus\CreateEmployeeStatus;
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

        // positions
        $positions = [
            trans('app.default_position_ceo'),
            trans('app.default_position_sales_representative'),
            trans('app.default_position_marketing_specialist'),
            trans('app.default_position_front_end_developer'),
        ];

        foreach ($positions as $position) {
            (new CreatePosition)->execute([
                'company_id' => $company->id,
                'author_id' => $employee->id,
                'title' => $position,
            ]);
        }

        // employee status
        $statuses = [
            trans('app.default_employee_status_full_time'),
            trans('app.default_employee_status_part_time'),
        ];

        foreach ($statuses as $status) {
            (new CreateEmployeeStatus)->execute([
                'company_id' => $company->id,
                'author_id' => $employee->id,
                'name' => $status,
            ]);
        }

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
