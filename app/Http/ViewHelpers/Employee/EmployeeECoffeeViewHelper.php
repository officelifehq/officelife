<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use App\Models\Company\ECoffeeMatch;

class EmployeeECoffeeViewHelper
{
    /**
     * List all the eCoffees the employee participated to.
     *
     * @param Employee $employee
     * @param Company $company
     * @return Collection|null
     */
    public static function index(Employee $employee, Company $company): ?Collection
    {
        $matches = ECoffeeMatch::where(function ($query) use ($employee) {
            $query->where('employee_id', $employee->id)
                ->orWhere('with_employee_id', $employee->id);
        })->orderBy('id', 'desc')
            ->with('eCoffee')
            ->with('employee')
            ->with('employeeMatchedWith')
            ->with('employee.position')
            ->with('employeeMatchedWith.position')
            ->get();

        $eCoffeeCollection = collect([]);
        foreach ($matches as $match) {
            if ($employee->id == $match->with_employee_id) {
                $withEmployee = $match->employee;
            } else {
                $withEmployee = $match->employeeMatchedWith;
            }

            $eCoffeeCollection->push([
                'id' => $match->id,
                'ecoffee' => [
                    'started_at' => DateHelper::formatDate($match->eCoffee->created_at),
                    'ended_at' => DateHelper::formatDate($match->eCoffee->created_at->endOfWeek(Carbon::SUNDAY)),
                ],
                'with_employee' => [
                    'id' => $withEmployee->id,
                    'name' => $withEmployee->name,
                    'first_name' => $withEmployee->first_name,
                    'avatar' => ImageHelper::getAvatar($withEmployee, 35),
                    'position' => $withEmployee->position ? $withEmployee->position->title : null,
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $withEmployee,
                    ]),
                ],
                'view_all_url' => route('employees.ecoffees.index', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ]);
        }

        return $eCoffeeCollection;
    }
}
