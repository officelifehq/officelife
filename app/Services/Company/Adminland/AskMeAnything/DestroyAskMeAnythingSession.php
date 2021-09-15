<?php

namespace App\Services\Company\Adminland\AskMeAnything;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\AskMeAnythingSession;

class DestroyAskMeAnythingSession extends BaseService
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
            'ask_me_anything_session_id' => 'required|integer|exists:ask_me_anything_sessions,id',
        ];
    }

    /**
     * Delete a AMA session.
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->destroySession();
        $this->log();
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
    }

    private function destroySession(): void
    {
        $this->session->delete();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'ask_me_anything_session_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([]),
        ])->onQueue('low');
    }
}
