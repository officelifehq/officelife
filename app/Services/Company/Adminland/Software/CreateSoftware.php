<?php

namespace App\Services\Company\Adminland\Software;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Software;

class CreateSoftware extends BaseService
{
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
            'product_key' => 'required|string|max:255',
            'seats' => 'required|integer',
            'licensed_to_name' => 'nullable|string|max:255',
            'licensed_to_email_address' => 'nullable|email|max:255',
            'order_number' => 'nullable|string|max:255',
            'purchase_cost' => 'nullable|integer',
            'currency' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date_format:Y-m-d',
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
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $software = Software::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'serial_number' => $this->valueOrNull($data, 'serial_number'),
            'website' => $this->valueOrNull($data, 'website'),
            'product_key' => $this->valueOrNull($data, 'product_key'),
            'seats' => $data['seats'],
            'licensed_to_name' => $this->valueOrNull($data, 'licensed_to_name'),
            'licensed_to_email_address' => $this->valueOrNull($data, 'licensed_to_email_address'),
            'order_number' => $this->valueOrNull($data, 'order_number'),
            'purchase_cost' => $this->valueOrNull($data, 'purchase_cost'),
            'currency' => $this->valueOrNull($data, 'currency'),
            'purchase_date' => $this->valueOrNull($data, 'purchase_date'),
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'software_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'software_id' => $software->id,
                'software_name' => $software->name,
            ]),
        ])->onQueue('low');

        return $software;
    }
}
