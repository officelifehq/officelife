<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Models\Company\Company;

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
}
