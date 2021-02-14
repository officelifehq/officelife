<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Helpers\DateHelper;
use App\Models\Company\Company;
use Illuminate\Support\Collection;
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
                'avatar' => $employee->avatar,
                'url_view' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ]);
        }

        // creation date of the account
        $creationDate = DateHelper::formatShortDateWithTime($company->created_at);

        return [
            'id' => $company->id,
            'name' => $name,
            'administrators' => $administratorsCollection,
            'creation_date' => $creationDate,
            'currency' => $company->currency,
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
