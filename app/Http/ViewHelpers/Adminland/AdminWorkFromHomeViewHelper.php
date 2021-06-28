<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Models\Company\Company;

class AdminWorkFromHomeViewHelper
{
    /**
     * Get the information about the work from home process
     * used in the company.
     *
     * @param Company $company
     * @return array|null
     */
    public static function index(Company $company): ?array
    {
        return [
            'enabled' => $company->work_from_home_enabled,
        ];
    }
}
