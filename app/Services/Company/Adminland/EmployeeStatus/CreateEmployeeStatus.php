<?php

namespace App\Services\Company\Adminland\EmployeeStatus;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\EmployeeStatus;

class CreateEmployeeStatus extends BaseService
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
            'name' => 'required|string|max:255',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Create an employee status.
     *
     * @param array $data
     * @return EmployeeStatus
     */
    public function execute(array $data) : EmployeeStatus
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('kakene.authorizations.hr')
        );

        $employeeStatus = EmployeeStatus::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_status_created',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_status_id' => $employeeStatus->id,
                'employee_status_name' => $employeeStatus->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        return $employeeStatus;
    }
}
