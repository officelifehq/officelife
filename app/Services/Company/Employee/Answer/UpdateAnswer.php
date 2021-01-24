<?php

namespace App\Services\Company\Employee\Answer;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Answer;
use App\Models\Company\Question;
use App\Exceptions\NotEnoughPermissionException;

class UpdateAnswer extends BaseService
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
            'answer_id' => 'required|integer|exists:answers,id',
            'body' => 'required|string|max:65535',
        ];
    }

    /**
     * Update an answer.
     *
     * @param array $data
     *
     * @throws NotEnoughPermissionException
     *
     * @return Answer
     */
    public function execute(array $data): Answer
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfEmployee($data['employee_id'])
            ->canExecuteService();

        $this->validateEmployeeBelongsToCompany($data);

        $answer = Answer::findOrFail($data['answer_id']);

        Question::where('company_id', $data['company_id'])
            ->findOrFail($answer->question->id);

        Answer::where('id', $answer->id)->update([
            'body' => $data['body'],
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'answer_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'answer_id' => $answer->id,
                'question_id' => $answer->question->id,
                'question_title' => $answer->question->title,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $data['employee_id'],
            'action' => 'answer_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'answer_id' => $answer->id,
                'question_id' => $answer->question->id,
                'question_title' => $answer->question->title,
            ]),
        ])->onQueue('low');

        $answer->refresh();

        return $answer;
    }
}
