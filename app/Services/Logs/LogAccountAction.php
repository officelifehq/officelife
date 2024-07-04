<?php

namespace App\Services\Logs;

use App\Models\Company\AuditLog;
use App\Services\BaseService;

class LogAccountAction extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'author_name' => 'required|string|max:255',
            'audited_at' => 'required|date',
            'action' => 'required|string|max:255',
            'objects' => 'required|json',
            'ip_address' => 'nullable|ipv4',
        ];
    }

    /**
     * Log an action that happened in a company.
     * This service is used in the Audit Log screen in the Adminland, and
     * therefore should only be used to log important actions.
     */
    public function execute(array $data): AuditLog
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        return AuditLog::create([
            'company_id' => $data['company_id'],
            'author_id' => $data['author_id'],
            'author_name' => $data['author_name'],
            'audited_at' => $data['audited_at'],
            'action' => $data['action'],
            'objects' => $data['objects'],
            'ip_address' => $this->valueOrNull($data, 'ip_address'),
        ]);
    }
}
