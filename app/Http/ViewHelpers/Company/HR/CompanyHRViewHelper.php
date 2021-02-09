<?php

namespace App\Http\ViewHelpers\Company\HR;

use Carbon\Carbon;
use OutOfRangeException;
use App\Helpers\DateHelper;
use Illuminate\Support\Str;
use App\Helpers\StringHelper;
use App\Helpers\BirthdayHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Services\Company\GuessEmployeeGame\CreateGuessEmployeeGame;

class CompanyHRViewHelper
{
    /**
     * Array containing information about the ecoffees in the company.
     *
     * @param Company $company
     * @return array
     */
    public static function eCoffees(Company $company): array
    {

        $teams = $company->teams->count();
        $employees = $company->employees()->notLocked()->count();
1
        return [
            'number_of_teams' => $teams,
            'number_of_employees' => $employees,
        ];
    }
}
