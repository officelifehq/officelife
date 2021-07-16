<?php

namespace App\Http\ViewHelpers\Company\HR;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use App\Models\Company\Position;
use Illuminate\Support\Collection;
use App\Models\Company\EmployeePositionHistory;

class CompanyHRPositionShowViewHelper
{
    /**
     * Get the detail of a specific position.
     *
     * @param Company $company
     * @param Position $position
     * @return Collection|null
     */
    public static function show(Company $company, Position $position): ?Collection
    {
        $employees = $position->employees()->notLocked()->addSelect([
            'started_at' => EmployeePositionHistory::whereColumn('employee_id', 'employees.id')
                ->whereColumn('position_id', $position->id)
                ->select('started_at')
                ->last(),
        ])->get();

        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 22),
                'started_at' => DateHelper::formatDate($employee->started_at),
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ]);
        }

        return $newsCollection;
    }
}
