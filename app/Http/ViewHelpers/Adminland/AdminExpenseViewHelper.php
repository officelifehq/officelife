<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Helpers\ImageHelper;
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
                'avatar' => ImageHelper::getAvatar($employee, 23),
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ]);
        }

        return $employeesCollection;
    }

    /**
     * Search all employees matching a given criteria.
     *
     * @param Company $company
     * @param string|null $criteria
     * @return Collection
     */
    public static function search(Company $company, ?string $criteria): Collection
    {
        return $company->employees()
            ->select('id', 'first_name', 'last_name', 'avatar_file_id')
            ->notLocked()
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('email', 'LIKE', '%'.$criteria.'%');
            })
            ->where('can_manage_expenses', false)
            ->orderBy('last_name', 'asc')
            ->take(10)
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                ];
            });
    }
}
