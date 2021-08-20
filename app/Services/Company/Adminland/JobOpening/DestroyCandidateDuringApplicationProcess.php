<?php

namespace App\Services\Company\Adminland\JobOpening;

use App\Services\BaseService;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;

class DestroyCandidateDuringApplicationProcess extends BaseService
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
            'job_opening_id' => 'required|integer|exists:job_openings,id',
            'candidate_id' => 'required|integer|exists:candidates,id',
        ];
    }

    /**
     * Destroy a candidate.
     *
     * @param array $data
     * @return bool
     */
    public function execute(array $data): bool
    {
        $this->validateRules($data);

        JobOpening::where('company_id', $data['company_id'])
            ->findOrFail($data['job_opening_id']);

        $candidate = Candidate::where('job_opening_id', $data['job_opening_id'])
            ->findOrFail($data['candidate_id']);

        foreach ($candidate->files as $file) {
            $file->delete();
        }

        $candidate->delete();

        return true;
    }
}
