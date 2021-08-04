<?php

namespace App\Services\Company\Adminland\JobOpening;

use App\Models\Company\File;
use App\Services\BaseService;
use App\Models\Company\Candidate;

class DestroyCandidateFileDuringApplicationProcess extends BaseService
{
    protected array $data;
    protected Candidate $candidate;
    protected File $file;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'candidate_id' => 'required|integer|exists:candidates,id',
            'file_id' => 'required|integer|exists:files,id',
        ];
    }

    /**
     * Destroy a file that belongs to a candidate during the interview process.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->destroyFile();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->candidate = Candidate::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['candidate_id']);

        $this->file = File::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['file_id']);
    }

    private function destroyFile(): void
    {
        /* @phpstan-ignore-next-line */
        $this->candidate->files()->detach($this->data['file_id']);
        $this->file->delete();
    }
}
