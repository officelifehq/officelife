<?php

namespace App\Services\Company\Employee\DisciplineCase;

use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\DisciplineCase;

class CreateDisciplineCase extends BaseService
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
        ];
    }

    /**
     * Create a discipline case.
     *
     * @param array $data
     * @return DisciplineCase
     */
    public function execute(array $data): DisciplineCase
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfManager($data['author_id'], $data['employee_id'])
            ->canExecuteService();

        Employee::where('company_id', $data['company_id'])
            ->findOrFail($data['employee_id']);

        $case = DisciplineCase::create([
            'company_id' => $data['company_id'],
            'employee_id' => $data['employee_id'],
            'opened_by_employee_id' => $data['author_id'],
            'opened_by_employee_name' => $this->author->name,
            'active' => true,
        ]);

        return $case;
    }
}
