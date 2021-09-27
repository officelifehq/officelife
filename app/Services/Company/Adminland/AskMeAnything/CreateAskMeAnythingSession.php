<?php

namespace App\Services\Company\Adminland\AskMeAnything;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\AskMeAnythingSession;

class CreateAskMeAnythingSession extends BaseService
{
    private array $data;
    private AskMeAnythingSession $session;

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
            'theme' => 'nullable|string|max:255',
            'date' => 'required|date_format:Y-m-d',
        ];
    }

    /**
     * Create a AMA session.
     *
     * @param array $data
     * @return AskMeAnythingSession
     */
    public function execute(array $data): AskMeAnythingSession
    {
        $this->data = $data;
        $this->validate();
        $this->createSession();
        $this->log();

        return $this->session;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();
    }

    private function createSession(): void
    {
        $this->session = AskMeAnythingSession::create([
            'company_id' => $this->data['company_id'],
            'happened_at' => $this->data['date'],
            'theme' => $this->valueOrNull($this->data, 'theme'),
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'ask_me_anything_session_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'ask_me_anything_session_id' => $this->session->id,
            ]),
        ])->onQueue('low');
    }
}
