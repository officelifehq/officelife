<?php

namespace App\Services\Company\Adminland\ExpenseCategory;

use App\Jobs\LogAccountAudit;
use App\Models\Company\ExpenseCategory;
use App\Services\BaseService;
use Carbon\Carbon;

class UpdateExpenseCategory extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'expense_category_id' => 'required|integer|exists:expense_categories,id',
            'name' => 'required|string|max:255',
        ];
    }

    /**
     * Update an expense category.
     */
    public function execute(array $data): ExpenseCategory
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $category = ExpenseCategory::where('company_id', $data['company_id'])
            ->findOrFail($data['expense_category_id']);

        $oldStatusName = $category->name;

        $category->update([
            'name' => $data['name'],
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'expense_category_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'expense_category_id' => $category->id,
                'expense_category_old_name' => $oldStatusName,
                'expense_category_new_name' => $data['name'],
            ]),
        ])->onQueue('low');

        return $category;
    }
}
