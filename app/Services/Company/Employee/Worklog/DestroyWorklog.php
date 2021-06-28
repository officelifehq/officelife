<?php

namespace App\Services\Company\Employee\Worklog;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Worklog;

class DestroyWorklog extends BaseService
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
            'employee_id' => 'required|integer|exists:employees,id',
            'worklog_id' => 'required|integer|exists:worklogs,id',
        ];
    }

    /**
     * Destroy a work log.
     *
     * @param array $data
     * @return bool
     */
    public function execute(array $data): bool
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfEmployeeOrManager($data['author_id'], $data['employee_id'])
            ->canExecuteService();

        $this->validateEmployeeBelongsToCompany($data);

        $worklog = Worklog::where('employee_id', $data['employee_id'])
            ->findOrFail($data['worklog_id']);

        $worklog->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'worklog_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'date' => $worklog->created_at->format('Y-m-d'),
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $data['employee_id'],
            'action' => 'worklog_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'date' => $worklog->created_at->format('Y-m-d'),
            ]),
        ])->onQueue('low');

        return true;
    }
}
