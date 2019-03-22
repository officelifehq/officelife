<?php

namespace App\Services\Adminland\Company;

use App\Services\BaseService;
use App\Models\Company\AuditLog;

class LogAction extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
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
