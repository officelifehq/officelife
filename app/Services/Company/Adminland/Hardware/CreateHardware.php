<?php

namespace App\Services\Company\Adminland\Hardware;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Hardware;

class CreateHardware extends BaseService
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
            'serial_number' => 'nullable|string|max:255',
        ];
    }

    /**
     * Create a hardware.
     *
     * @param array $data
     *
     * @return Hardware
     */
    public function execute(array $data): Hardware
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $hardware = Hardware::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
            'serial_number' => $this->valueOrNull($data, 'serial_number'),
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'hardware_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'hardware_id' => $hardware->id,
                'hardware_name' => $hardware->name,
            ]),
        ])->onQueue('low');

        return $hardware;
    }
}
