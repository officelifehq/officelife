<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Helpers\DateHelper;
use App\Models\Company\Company;
use Illuminate\Support\Collection;
use App\Models\Company\CompanyInvoice;

class AdminBillingViewHelper
{
    /**
     * Get all the information about the account usage.
     *
     * @param Company $company
     * @return Collection|null
     */
    public static function index(Company $company): ?Collection
    {
        return $company->invoices()
            ->with('companyUsageHistory')
            ->latest()
            ->get()
            ->map(function ($invoice) use ($company) {
                return [
                    'id' => $invoice->id,
                    'number_of_active_employees' => $invoice->companyUsageHistory->number_of_active_employees,
                    'month' => DateHelper::formatMonthAndYear($invoice->created_at),
                    'url' => route('invoices.show', [
                        'company' => $company,
                        'invoice' => $invoice->id,
                    ]),
                ];
            });
    }

    /**
     * Get the invoice information.
     *
     * @param CompanyInvoice $invoice
     * @return array|null
     */
    public static function show(CompanyInvoice $invoice): ?array
    {
        $companyUsageHistory = $invoice->companyUsageHistory;

        $details = $companyUsageHistory->details()->get()
            ->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'employee_name' => $detail->employee_name,
                    'employee_email' => $detail->employee_email,
                ];
            });

        return [
            'month' => DateHelper::formatMonthAndYear($invoice->created_at),
            'day_with_max_employees' => DateHelper::formatDate($companyUsageHistory->created_at),
            'number_of_active_employees' => $companyUsageHistory->number_of_active_employees,
            'details' => $details,
        ];
    }
}
