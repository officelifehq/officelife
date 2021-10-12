<?php

namespace App\Services\Company\Adminland\Company;

use Carbon\Carbon;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Services\Company\Project\CreateIssueType;
use App\Services\Company\Adminland\ExpenseCategory\CreateExpenseCategory;
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
        Employee::where('id', $employee->id)->update([
            'amount_of_allowed_holidays' => $company->getCurrentPTOPolicy()->default_amount_of_allowed_holidays,
        ]);

        // create expense categories
        $listOfCategories = [
            trans('account.expense_category_default_maintenance_and_repairs'),
            trans('account.expense_category_default_meals_and_entertainment'),
            trans('account.expense_category_default_office_expense'),
            trans('account.expense_category_default_travel'),
            trans('account.expense_category_default_motor_vehicle_expenses'),
        ];

        foreach ($listOfCategories as $category) {
            $request = [
                'company_id' => $company->id,
                'author_id' => $employee->id,
                'name' => $category,
            ];

            (new CreateExpenseCategory)->execute($request);
        }

        // create default issue type
        $listOfIssues = config('officelife.issue_types');
        foreach ($listOfIssues as $name => $hexColor) {
            $request = [
                'company_id' => $company->id,
                'author_id' => $employee->id,
                'name' => trans('account.issue_type_'.$name),
                'icon_hex_color' => $hexColor,
            ];

            (new CreateIssueType)->execute($request);
        }
    }
}
