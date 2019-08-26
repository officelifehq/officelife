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
            'author_id' => 'required|integer|exists:employees,id',
            'author_name' => 'required|string|max:255',
            'audited_at' => 'required|date',
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
            'author_id' => $data['author_id'],
            'author_name' => $data['author_name'],
            'audited_at' => $data['audited_at'],
            'action' => $data['action'],
            'objects' => $data['objects'],
            'ip_address' => $this->nullOrValue($data, 'ip_address'),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);
    }
}
