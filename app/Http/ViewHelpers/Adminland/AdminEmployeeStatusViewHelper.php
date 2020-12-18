<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Models\Company\Company;
use Illuminate\Support\Collection;

class AdminEmployeeStatusViewHelper
{
    /**
     * Collection containing information about all the employee statuses in the
     * account.
     *
     * @param Company $company
     * @return Collection
     */
    public static function index(Company $company): Collection
    {
        $employeeStatuses = $company->employeeStatuses()
            ->orderBy('name', 'asc')
            ->get();

        $statusesCollection = collect([]);
        foreach ($employeeStatuses as $status) {
            $statusesCollection->push([
                'id' => $status->id,
                'name' => $status->name,
                'type' => $status->type,
                'type_translated' => trans('account.employee_statuses_'.$status->type),
            ]);
        }

        return $statusesCollection;
    }
}
