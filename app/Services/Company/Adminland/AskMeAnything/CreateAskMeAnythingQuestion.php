<?php

namespace App\Services\Company\Adminland\AskMeAnything;

use App\Services\BaseService;
use App\Models\Company\AskMeAnythingSession;
use App\Models\Company\AskMeAnythingQuestion;

class CreateAskMeAnythingQuestion extends BaseService
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
            'question' => 'required|string|max:255',
            'anonymous' => 'required|boolean',
        ];
    }

    /**
     * Create an AMA question.
     * We don't log this interaction because it's against the anonymous nature.
     *
     * @param array $data
     * @return AskMeAnythingQuestion
     */
    public function execute(array $data): AskMeAnythingQuestion
    {
        $this->data = $data;
        $this->validate();
        $this->createQuestion();

        return $this->question;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->session = AskMeAnythingSession::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['ask_me_anything_session_id']);
    }

    private function createQuestion(): void
    {
        $this->question = AskMeAnythingQuestion::create([
            'ask_me_anything_session_id' => $this->session->id,
            'employee_id' => $this->author->id,
            'anonymous' => $this->data['anonymous'],
            'question' => $this->data['question'],
        ]);
    }
}
