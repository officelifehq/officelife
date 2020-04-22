<?php

namespace App\Http\ViewHelpers\Company\Adminland;

use App\Models\Company\Company;
use Illuminate\Support\Collection;

class AdminHardwareViewHelper
{
    /**
     * Collection containing all the information about the hardware used in the company.
     *
     * @param Company $company
     * @return array|null
     */
    public static function hardware(Company $company): ?array
    {
        // get all hardware
        $hardware = $company->hardware()->with('employee')->get();

        // if no hardware
        if ($hardware->count() == 0) {
            return null;
        }

        // building a collection of hardware
        $hardwareCollection = collect([]);
        foreach ($hardware as $piece) {
            $employee = $piece->employee;

            $hardwareCollection->push([
                'id' => $piece->id,
                'name' => $piece->name,
                'employee' => ($employee) ? [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => $employee->avatar,
                ] : null,
            ]);
        }

        // statistics
        $numberOfHardwareNotLent = $hardware->filter(function ($piece) {
            return is_null($piece->employee);
        })->count();

        return [
            'hardware_collection' => $hardwareCollection,
            'number_hardware_not_lent' => $numberOfHardwareNotLent,
            'number_hardware_lent' => $hardware->count() - $numberOfHardwareNotLent,
        ];
    }
}
