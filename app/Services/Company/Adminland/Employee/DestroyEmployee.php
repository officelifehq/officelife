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
    public function rules() : array
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
     * @return void
     */
    public function execute(array $data) : void
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('villagers.authorizations.administrator')
        );

        $employee = Employee::where('company_id', $data['company_id'])
            ->findOrFail($data['employee_id']);

        $employee->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_destroyed',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_name' => $employee->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');
    }
}
