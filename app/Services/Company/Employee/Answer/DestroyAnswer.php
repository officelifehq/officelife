<?php

namespace App\Services\Company\Employee\Answer;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Answer;
use App\Models\Company\Question;

class DestroyAnswer extends BaseService
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
        ];
    }

    /**
     * Destroy an answer.
     *
     * @param array $data
     *
     * @return bool
     */
    public function execute(array $data): bool
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfEmployee($data['employee_id'])
            ->canExecuteService();

        $this->validateEmployeeBelongsToCompany($data);

        $answer = Answer::findOrFail($data['answer_id']);
        $question = Question::where('company_id', $data['company_id'])
            ->findOrFail($answer->question->id);

        $answer->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'answer_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'question_id' => $question->id,
                'question_title' => $question->title,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $data['employee_id'],
            'action' => 'answer_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'question_id' => $answer->question->id,
                'question_title' => $answer->question->title,
            ]),
        ])->onQueue('low');

        return true;
    }
}
