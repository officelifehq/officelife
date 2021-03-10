<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Helpers\AvatarHelper;
use App\Models\Company\Company;
use Illuminate\Support\Collection;

class AdminExpenseViewHelper
{
    /**
     * Collection containing all the information about the expense categories
     * used in the company.
     *
     * @param mixed $company
     * @return Collection|null
     */
    public static function categories($company): ?Collection
    {
        $categories = $company->expenseCategories()->orderBy('name', 'asc')->get();
        $categoriesCollection = collect([]);
        foreach ($categories as $category) {
            $categoriesCollection->push([
                'id' => $category->id,
                'name' => $category->name,
            ]);
        }

        return $categoriesCollection;
    }

    /**
     * Collection containing all the employees who have the right to manage
     * expenses in the company.
     *
     * @param mixed $company
     * @return Collection|null
     */
    public static function employees($company): ?Collection
    {
        $employees = $company->employees()
            ->where('employees.can_manage_expenses', true)
            ->get();

        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => AvatarHelper::getImage($employee),
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ]);
        }

        return $employeesCollection;
    }
}
