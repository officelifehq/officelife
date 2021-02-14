<?php

namespace App\Services\Company\Employee\RateYourManager;

use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\RateYourManagerAnswer;
use App\Exceptions\NotEnoughPermissionException;
use App\Exceptions\SurveyNotActiveAnymoreException;

class AddCommentToRatingAboutManager extends BaseService
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
            'comment' => 'required|string|max:65535',
            'reveal_identity_to_manager' => 'boolean',
        ];
    }

    /**
     * Save the Rate your manager survey's comment about the manager from the
     * employee.
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

        $answer->comment = $data['comment'];
        $answer->reveal_identity_to_manager = $data['reveal_identity_to_manager'];
        $answer->save();

        return $answer;
    }
}
