<?php

namespace App\Services\Company\Employee\RateYourManager;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\RateYourManagerAnswer;
use App\Exceptions\NotEnoughPermissionException;
use App\Exceptions\SurveyNotActiveAnymoreException;

class RateYourManager extends BaseService
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
            'answer_id' => 'required|integer|exists:rate_your_manager_answers,id',
            'rating' => 'required|string|max:255',
        ];
    }

    /**
     * Save the Rate your manager survey's answer from the employee.
     *
     * @param array $data
     * @return RateYourManagerAnswer
     */
    public function execute(array $data): RateYourManagerAnswer
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $answer = RateYourManagerAnswer::findOrFail($data['answer_id']);
        $survey = $answer->entry;

        if ($survey->manager->company_id != $data['company_id']) {
            throw new NotEnoughPermissionException();
        }

        if (! $survey->active) {
            throw new SurveyNotActiveAnymoreException();
        }

        if ($answer->employee_id != $this->author->id) {
            throw new NotEnoughPermissionException();
        }

        $answer->rating = $data['rating'];
        $answer->active = false;
        $answer->save();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'rate_your_manager_survey_answered',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'manager_id' => $survey->manager->id,
                'manager_name' => $survey->manager->name,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->author->id,
            'action' => 'rate_your_manager_survey_answered',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'manager_id' => $survey->manager->id,
                'manager_name' => $survey->manager->name,
            ]),
        ])->onQueue('low');

        return $answer;
    }
}
