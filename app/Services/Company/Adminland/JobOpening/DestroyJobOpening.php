<?php

namespace App\Services\Company\Adminland\JobOpening;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\JobOpening;

class DestroyJobOpening extends BaseService
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
            'job_opening_id' => 'required|integer|exists:job_openings,id',
        ];
    }

    /**
     * Destroy a job opening.
     *
     * @param array $data
     * @return bool
     */
    public function execute(array $data): bool
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $jobOpening = JobOpening::where('company_id', $data['company_id'])
            ->findOrFail($data['job_opening_id']);

        $jobOpening->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'job_opening_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'job_opening_title' => $jobOpening->title,
            ]),
        ])->onQueue('low');

        return true;
    }
}
