<?php

namespace App\Services\Company\Adminland\Employee;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;

class DestroyEmployee extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|exists:employees,id|integer',
            'company_id' => 'required|exists:companies,id|integer',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Delete an employee.
     *
     * @param array $data
     *
     */
    public function execute(array $data): void
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $employee = $this->validateEmployeeBelongsToCompany($data);

        $employee->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_name' => $employee->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');
    }
}
