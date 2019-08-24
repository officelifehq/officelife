<?php

namespace App\Services\Logs;

use App\Services\BaseService;
use App\Models\Company\AuditLog;

class LogAccountAction extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'action' => 'required|string|max:255',
            'objects' => 'required|json',
            'ip_address' => 'nullable|ipv4',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Log an action that happened in a company.
     * This service is used in the Audit Log screen in the Adminland, and
     * therefore should only be used to log important actions.
     *
     * @param array $data
     * @return AuditLog
     */
    public function execute(array $data) : AuditLog
    {
        $this->validate($data);

        return AuditLog::create([
            'company_id' => $data['company_id'],
            'action' => $data['action'],
            'objects' => $data['objects'],
            'ip_address' => $this->nullOrValue($data, 'ip_address'),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);
    }
}
