<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\File;
use App\Models\Company\Company;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Money\Currencies\ISOCurrencies;

class AdminGeneralViewHelper
{
    /**
     * Get all the information about the current company.
     *
     * @param mixed $company
     * @return array|null
     */
    public static function information($company): ?array
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
        $creationDate = DateHelper::formatShortDateWithTime($company->created_at);

        // total file sizes
        $totalSize = DB::table('files')->where('company_id', $company->id)
            ->sum('size');

        // logo
        $logo = $company->logo ? ImageHelper::getImage($company->logo, 300, 300) : null;

        return [
            'id' => $company->id,
            'name' => $name,
            'administrators' => $administratorsCollection,
            'creation_date' => $creationDate,
            'currency' => $company->currency,
            'total_size' => round($totalSize / 1000, 4),
            'logo' => $logo,
            'uploadcare_public_key' => config('officelife.uploadcare_public_key'),
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
