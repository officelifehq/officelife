<?php

namespace App\Services\Company\Adminland\Question;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Question;
use App\Exceptions\NotEnoughPermissionException;

class ActivateQuestion extends BaseService
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
     * Activate a question.
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
            'active' => true,
            'activated_at' => Carbon::now(),
            'deactivated_at' => null,
        ]);

        $question->refresh();

        $this->disableAllOtherQuestions($question);

        $this->log($data, $question);

        return $question->refresh();
    }

    /**
     * Mark all the other questions as inactive.
     *
     * @param Question $question
     */
    private function disableAllOtherQuestions(Question $question): void
    {
        $company = $question->company;
        $questions = $company->questions()
            ->where('id', '!=', $question->id)
            ->get();

        foreach ($questions as $question) {
            Question::where('id', $question->id)->update([
                'active' => false,
                'deactivated_at' => $question->active ? Carbon::now() : null,
            ]);
        }
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
            'action' => 'question_activated',
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
