<?php

namespace App\Services\Company\Adminland\AskMeAnything;

use App\Services\BaseService;
use App\Models\Company\AskMeAnythingSession;
use App\Models\Company\AskMeAnythingQuestion;

class DestroyAskMeAnythingQuestion extends BaseService
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
     * Delete a AMA question.
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->destroyQuestion();
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

    private function destroyQuestion(): void
    {
        $this->question->delete();
    }
}
