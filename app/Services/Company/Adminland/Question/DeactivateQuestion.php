<?php

namespace App\Services\Company\Adminland\Question;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Question;
use App\Exceptions\NotEnoughPermissionException;

class DeactivateQuestion extends BaseService
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
            'question_id' => 'required|integer|exists:questions,id',
        ];
    }

    /**
     * Deactivate a question.
     *
     * @param array $data
     *
     * @throws NotEnoughPermissionException
     *
     * @return Question
     */
    public function execute(array $data): Question
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $question = Question::where('company_id', $data['company_id'])
            ->findOrFail($data['question_id']);

        Question::where('id', $question->id)->update([
            'active' => false,
            'deactivated_at' => Carbon::now(),
        ]);

        $question->refresh();

        $this->log($data, $question);

        return $question->refresh();
    }

    /**
     * Create an audit log.
     *
     * @param array    $data
     * @param Question $question
     */
    private function log(array $data, Question $question): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'question_deactivated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'question_id' => $question->id,
                'question_title' => $question->title,
            ]),
        ])->onQueue('low');
    }
}
