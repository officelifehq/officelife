<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Models\Company\Company;

class AdminECoffeeViewHelper
{
    /**
     * Get the information about the ecoffee process
     * used in the company.
     *
     * @param Company $company
     * @return array|null
     */
    public static function eCoffee(Company $company): ?array
    {
        return [
            'enabled' => $company->e_coffee_enabled,
        ];
    }
}
