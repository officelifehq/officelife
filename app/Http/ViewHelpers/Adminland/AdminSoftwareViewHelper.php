<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Models\Company\Company;
use Illuminate\Support\Collection;

class AdminSoftwareViewHelper
{
    /**
     * Get all the softwares in the company.
     *
     * @param mixed $softwares
     * @param Company $company
     * @return Collection
     */
    public static function index($softwares, Company $company): Collection
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

        return $softwareCollection;
    }
}
