<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\File;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Money\Currencies\ISOCurrencies;

class AdminGeneralViewHelper
{
    /**
     * Get all the information about the current company.
     *
     * @param mixed $company
     * @param Employee $loggedEmployee
     * @return array|null
     */
    public static function information($company, Employee $loggedEmployee): ?array
    {
        $name = $company->name;

        // list of curent administrators
        $administratorsCollection = collect([]);
        $administrators = $company->employees()
            ->notLocked()
            ->where('permission_level', 100)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($administrators as $employee) {
            $administratorsCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 22),
                'url_view' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ]);
        }

        // creation date of the account
        $creationDate = DateHelper::formatShortDateWithTime($company->created_at, $loggedEmployee->timezone);

        // total file sizes
        $totalSize = DB::table('files')->where('company_id', $company->id)
            ->sum('size');

        // logo
        $logo = $company->logo ? ImageHelper::getImage($company->logo, 300, 300) : null;

        // founded date
        $foundedDate = $company->founded_at ? $company->founded_at->year : null;

        // code to invite employees
        $invitationCode = $company->code_to_join_company;

        return [
            'id' => $company->id,
            'name' => $name,
            'slug' => $company->slug,
            'administrators' => $administratorsCollection,
            'creation_date' => $creationDate,
            'currency' => $company->currency,
            'location' => $company->location,
            'total_size' => round($totalSize / 1000, 4),
            'logo' => $logo,
            'uploadcare_public_key' => config('officelife.uploadcare_public_key'),
            'founded_at' => $foundedDate,
            'invitation_code' => $invitationCode,
        ];
    }

    /**
     * Get all the currencies used in the instance.
     *
     * @return Collection|null
     */
    public static function currencies(): ?Collection
    {
        $currencyCollection = collect([]);
        $currencies = new ISOCurrencies();
        foreach ($currencies as $currency) {
            $currencyCollection->push([
                'code' => $currency->getCode(),
            ]);
        }

        return $currencyCollection;
    }
}
