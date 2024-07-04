<?php

namespace App\Services\Company\Employee\DisciplineCase;

use App\Models\Company\DisciplineCase;
use App\Models\Company\DisciplineEvent;
use App\Services\BaseService;

class DestroyDisciplineEvent extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'discipline_case_id' => 'required|integer|exists:discipline_cases,id',
            'discipline_event_id' => 'required|integer|exists:discipline_events,id',
        ];
    }

    /**
     * Destroy a discipline event.
     */
    public function execute(array $data): bool
    {
        $this->validateRules($data);

        $disciplineCase = DisciplineCase::where('company_id', $data['company_id'])
            ->findOrFail($data['discipline_case_id']);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfManager($data['author_id'], $disciplineCase->employee->id)
            ->canExecuteService();

        $event = DisciplineEvent::where('discipline_case_id', $disciplineCase->id)
            ->findOrFail($data['discipline_event_id']);

        $event->delete();

        return true;
    }
}
