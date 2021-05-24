<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use App\Models\Company\Software;
use Illuminate\Support\Collection;

class AdminSoftwareViewHelper
{
    /**
     * Get all the softwares in the company.
     *
     * @param mixed $softwares
     * @param Company $company
     * @return array
     */
    public static function index($softwares, Company $company): array
    {
        $softwareCollection = collect([]);
        foreach ($softwares as $software) {
            $employeeCount = $software->employees()->count();

            $softwareCollection->push([
                'id' => $software->id,
                'name' => $software->name,
                'seats' => $software->seats,
                'remaining_seats' => $software->seats - $employeeCount,
                'url' => route('software.show', [
                    'company' => $company,
                    'software' => $software,
                ]),
            ]);
        }

        return [
            'softwares' => $softwareCollection,
            'url_new' => route('software.create', [
                'company' => $company,
            ]),
        ];
    }

    /**
     * Shows the details of a given software.
     *
     * @param Software $software
     * @return array
     */
    public static function show(Software $software): array
    {
        return [
            'id' => $software->id,
            'name' => $software->name,
            'website' => $software->website,
            'product_key' => $software->product_key,
            'seats' => $software->seats,
            'licensed_to_name' => $software->licensed_to_name,
            'licensed_to_email_address' => $software->licensed_to_email_address,
            'order_number' => $software->order_number,
            'purchase_amount' => $software->purchase_amount,
            'currency' => $software->currency,
            'converted_purchase_amount' => $software->converted_purchase_amount,
            'converted_to_currency' => $software->converted_to_currency,
            'purchased_at' => $software->purchased_at ? DateHelper::formatDate($software->purchased_at) : null,
            'converted_at' => $software->converted_at ? DateHelper::formatDate($software->converted_at) : null,
            'exchange_rate' => $software->exchange_rate,
        ];
    }

    public static function seats($employees, Company $company): Collection
    {
        $employeesCollection = collect();
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 65),
                'product_key' => $employee->pivot->product_key,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ]);
        }

        return $employeesCollection;
    }
}
