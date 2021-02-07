<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Models\Company\Company;
use Illuminate\Support\Collection;

class AdminECoffeeViewHelper
{
    /**
     * Collection containing all the information about the expense categories
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
