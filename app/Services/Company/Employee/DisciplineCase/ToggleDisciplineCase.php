<?php

namespace App\Services\Company\Employee\DisciplineCase;

use App\Services\BaseService;
use App\Models\Company\DisciplineCase;

class ToggleDisciplineCase extends BaseService
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
            'discipline_case_id' => 'required|integer|exists:discipline_cases,id',
        ];
    }

    /**
     * Toggle the discipline case.
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
            ->canExecuteService();

        $case = DisciplineCase::where('company_id', $data['company_id'])
            ->findOrFail($data['discipline_case_id']);

        $case->active = ! $case->active;
        $case->save();

        return $case;
    }
}
