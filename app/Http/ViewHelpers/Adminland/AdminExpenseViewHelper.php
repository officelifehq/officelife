<?php

namespace App\Http\ViewHelpers\Adminland;

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
                'url' => route('account.expenses.show', [
                    'company' => $company->id,
                    'expense' => $category->id,
                ]),
            ]);
        }

        return $categoriesCollection;
    }
}
