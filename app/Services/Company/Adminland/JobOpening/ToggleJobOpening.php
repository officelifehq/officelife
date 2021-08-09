<?php

namespace App\Services\Company\Adminland\JobOpening;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\JobOpening;

class ToggleJobOpening extends BaseService
{
    protected array $data;
    protected JobOpening $opening;

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
            'job_opening_id' => 'required|integer|exists:job_openings,id',
        ];
    }

    /**
     * Toggle the job opening.
     *
     * @param array $data
     * @return JobOpening
     */
    public function execute(array $data): JobOpening
    {
        $this->data = $data;
        $this->validate();
        $this->toggle();
        $this->log();

        return $this->opening;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->opening = JobOpening::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['job_opening_id']);
    }

    private function toggle(): void
    {
        $this->opening->active = ! $this->opening->active;
        $this->opening->save();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'job_opening_toggled',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'job_opening_id' => $this->opening->id,
                'job_opening_title' => $this->opening->title,
            ]),
        ])->onQueue('low');
    }
}
