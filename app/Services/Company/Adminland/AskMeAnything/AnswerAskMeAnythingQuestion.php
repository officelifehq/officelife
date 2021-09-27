<?php

namespace App\Services\Company\Adminland\AskMeAnything;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\AskMeAnythingSession;
use App\Models\Company\AskMeAnythingQuestion;

class AnswerAskMeAnythingQuestion extends BaseService
{
    private array $data;
    private AskMeAnythingSession $session;
    private AskMeAnythingQuestion $question;

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
            'ask_me_anything_session_id' => 'required|integer|exists:ask_me_anything_sessions,id',
            'ask_me_anything_question_id' => 'required|integer|exists:ask_me_anything_questions,id',
        ];
    }

    /**
     * Mark a question as answered.
     *
     * @param array $data
     * @return AskMeAnythingQuestion
     */
    public function execute(array $data): AskMeAnythingQuestion
    {
        $this->data = $data;
        $this->validate();
        $this->updateQuestion();
        $this->log();

        return $this->question;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->session = AskMeAnythingSession::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['ask_me_anything_session_id']);

        $this->question = AskMeAnythingQuestion::where('ask_me_anything_session_id', $this->data['ask_me_anything_session_id'])
            ->findOrFail($this->data['ask_me_anything_question_id']);
    }

    private function updateQuestion(): void
    {
        $this->question->answered = true;
        $this->question->save();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'ask_me_anything_question_answered',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'ask_me_anything_session_id' => $this->session->id,
            ]),
        ])->onQueue('low');
    }
}
