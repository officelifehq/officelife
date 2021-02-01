<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Models\Company\Company;
use App\Models\Company\Employee;

class EmployeeEditContractViewHelper
{
    /**
     * Information about the contract.
     *
     * @param Employee $employee
     * @return array
     */
    public static function employeeInformation(Employee $employee): array
    {
        return [
            'id' => $employee->id,
            'name' => $employee->name,
            'year' => $employee->contract_renewed_at ? $employee->contract_renewed_at->year : null,
            'month' => $employee->contract_renewed_at ? $employee->contract_renewed_at->month : null,
            'day' => $employee->contract_renewed_at ? $employee->contract_renewed_at->day : null,
            'max_year' => Carbon::now()->addYears(10)->year,
        ];
    }

    /**
     * Get the consultant rates of the employee, if they exist.
     *
     * @param Employee $employee
     * @param Company $company
     * @return array
     */
    public static function rates(Employee $employee, Company $company): array
    {
        $rates = $employee->consultantRates()->orderBy('id', 'desc')->get();

        $ratesCollection = collect([]);
        foreach ($rates as $rate) {
            $ratesCollection->push([
                'id' => $rate->id,
                'rate' => $rate->rate,
                'active' => $rate->active,
            ]);
        }

        return [
            'company_currency' => $company->currency,
            'rates' => $ratesCollection,
        ];
    }
}
