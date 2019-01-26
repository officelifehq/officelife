<?php

namespace App\Services\Account\Account;

use App\Services\BaseService;
use App\Models\Account\AuditLog;

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
            'account_id' => 'required|integer|exists:accounts,id',
            'action' => 'required|string|max:255',
            'objects' => 'required|json',
            'ip_address' => 'nullable|ipv4',
        ];
    }

    /**
     * Log an action that happened in an account.
     *
     * @param array $data
     * @return AuditLog
     */
    public function execute(array $data) : AuditLog
    {
        $this->validate($data);

        return AuditLog::create([
            'account_id' => $data['account_id'],
            'action' => $data['action'],
            'objects' => $data['objects'],
            'ip_address' => $this->nullOrValue($data, 'ip_address'),
        ]);
    }
}
