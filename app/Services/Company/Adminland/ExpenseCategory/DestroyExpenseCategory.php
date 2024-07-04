<?php

namespace App\Services\Company\Adminland\ExpenseCategory;

use App\Jobs\LogAccountAudit;
use App\Models\Company\ExpenseCategory;
use App\Services\BaseService;
use Carbon\Carbon;

class DestroyExpenseCategory extends BaseService
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
        ];
    }

    /**
     * Destroy an expense category.
     */
    public function execute(array $data): bool
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $category = ExpenseCategory::where('company_id', $data['company_id'])
            ->findOrFail($data['expense_category_id']);

        $category->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'expense_category_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'expense_category_name' => $category->name,
            ]),
        ])->onQueue('low');

        return true;
    }
}
