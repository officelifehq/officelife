<?php

namespace App\Services\Company\Adminland\Company;

use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Services\Company\Adminland\Position\CreatePosition;
use App\Services\Company\Adminland\EmployeeStatus\CreateEmployeeStatus;

class ProvisionDefaultAccountData extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
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
     * @return void
     */
    public function execute(array $data) : void
    {
        $this->validate($data);

        // positions
        $positions = [
            trans('app.default_position_ceo'),
            trans('app.default_position_sales_representative'),
            trans('app.default_position_marketing_specialist'),
            trans('app.default_position_front_end_developer'),
        ];

        foreach ($positions as $position) {
            (new CreatePosition)->execute([
                'company_id' => $data['company_id'],
                'author_id' => $data['author_id'],
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
                'company_id' => $data['company_id'],
                'author_id' => $data['author_id'],
                'name' => $status,
            ]);
        }
    }
}
