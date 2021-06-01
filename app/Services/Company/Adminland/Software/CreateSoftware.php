<?php

namespace App\Services\Company\Adminland\Software;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Software;
use App\Jobs\ConvertSoftwarePurchase;

class CreateSoftware extends BaseService
{
    protected array $data;
    protected Software $software;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'name' => 'required|string|max:255',
            'website' => 'nullable|string|max:255',
            'product_key' => 'required|string|max:65000',
            'seats' => 'required|integer',
            'licensed_to_name' => 'nullable|string|max:255',
            'licensed_to_email_address' => 'nullable|email|max:255',
            'order_number' => 'nullable|string|max:255',
            'purchase_amount' => 'nullable|numeric',
            'currency' => 'nullable|string|max:255',
            'purchased_at' => 'nullable|date_format:Y-m-d',
        ];
    }

    /**
     * Create a software.
     *
     * @param array $data
     * @return Software
     */
    public function execute(array $data): Software
    {
        $this->data = $data;
        $this->validate();
        $this->create();
        $this->convertCurrency();
        $this->log();

        return $this->software;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();
    }

    private function create(): void
    {
        $currency = $this->valueOrNull($this->data, 'currency');
        if (is_null($currency)) {
            $currency = $this->author->company->currency;
        }

        $this->software = Software::create([
            'company_id' => $this->data['company_id'],
            'name' => $this->data['name'],
            'website' => $this->valueOrNull($this->data, 'website'),
            'product_key' => $this->valueOrNull($this->data, 'product_key'),
            'seats' => $this->data['seats'],
            'licensed_to_name' => $this->valueOrNull($this->data, 'licensed_to_name'),
            'licensed_to_email_address' => $this->valueOrNull($this->data, 'licensed_to_email_address'),
            'order_number' => $this->valueOrNull($this->data, 'order_number'),
            'purchase_amount' => $this->valueOrNull($this->data, 'purchase_amount') ? $this->data['purchase_amount'] * 100 : null,
            'currency' => $currency,
            'purchased_at' => $this->valueOrNull($this->data, 'purchased_at'),
        ]);
    }

    private function convertCurrency(): void
    {
        if (is_null($this->valueOrNull($this->data, 'purchase_amount'))) {
            return;
        }

        ConvertSoftwarePurchase::dispatch($this->software);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'software_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'software_id' => $this->software->id,
                'software_name' => $this->software->name,
            ]),
        ])->onQueue('low');
    }
}
