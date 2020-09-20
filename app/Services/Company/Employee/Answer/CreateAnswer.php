<?php

namespace App\Services\Company\Employee\Answer;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Answer;
use App\Models\Company\Question;
use App\Exceptions\NotEnoughPermissionException;

class CreateAnswer extends BaseService
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
            'question_id' => 'required|integer|exists:questions,id',
            'body' => 'required|string|max:65535',
        ];
    }

    /**
     * Create an answer.
     * There should be only one answer per question.
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

        $employee = $this->validateEmployeeBelongsToCompany($data);

        $question = Question::where('company_id', $data['company_id'])
            ->findOrFail($data['question_id']);

        $answer = Answer::updateOrCreate([
            'question_id' => $data['question_id'],
            'employee_id' => $data['employee_id'],
        ], [
            'body' => $data['body'],
        ], );

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'answer_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'answer_id' => $answer->id,
                'question_id' => $question->id,
                'question_title' => $question->title,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $employee->id,
            'action' => 'answer_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'answer_id' => $answer->id,
                'question_id' => $question->id,
                'question_title' => $question->title,
            ]),
        ])->onQueue('low');

        return $answer;
    }
}
