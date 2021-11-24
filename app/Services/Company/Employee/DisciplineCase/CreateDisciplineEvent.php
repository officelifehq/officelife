<?php

namespace App\Services\Company\Employee\DisciplineCase;

use App\Services\BaseService;
use App\Models\Company\DisciplineCase;
use App\Models\Company\DisciplineEvent;

class CreateDisciplineEvent extends BaseService
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
            'happened_at' => 'required|date_format:Y-m-d',
            'description' => 'required|string|max:65535',
        ];
    }

    /**
     * Create a discipline case's event.
     *
     * @param array $data
     * @return DisciplineEvent
     */
    public function execute(array $data): DisciplineEvent
    {
        $this->validateRules($data);

        $disciplineCase = DisciplineCase::where('company_id', $data['company_id'])
            ->findOrFail($data['discipline_case_id']);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfManager($data['author_id'], $disciplineCase->employee->id)
            ->canExecuteService();

        $event = DisciplineEvent::create([
            'company_id' => $data['company_id'],
            'discipline_case_id' => $data['discipline_case_id'],
            'author_id' => $data['author_id'],
            'author_name' => $this->author->name,
            'happened_at' => $data['happened_at'],
            'description' => $data['description'],
        ]);

        return $event;
    }
}
