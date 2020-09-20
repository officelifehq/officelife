<?php

namespace App\Services\Company\Adminland\Hardware;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Hardware;
use App\Models\Company\Position;

class DestroyHardware extends BaseService
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
            'hardware_id' => 'required|integer|exists:hardware,id',
        ];
    }

    /**
     * Destroy a position.
     *
     * @param array $data
     *
     * @return bool
     */
    public function execute(array $data): bool
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $hardware = Hardware::where('company_id', $data['company_id'])
            ->findOrFail($data['hardware_id']);

        $hardware->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'hardware_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'hardware_name' => $hardware->name,
            ]),
        ])->onQueue('low');

        return true;
    }
}
