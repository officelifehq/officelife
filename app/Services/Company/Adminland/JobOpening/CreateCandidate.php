<?php

namespace App\Services\Company\Adminland\JobOpening;

use Illuminate\Support\Str;
use App\Services\BaseService;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;

class CreateCandidate extends BaseService
{
    protected array $data;
    protected JobOpening $jobOpening;
    protected Candidate $candidate;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'job_opening_id' => 'required|integer|exists:job_openings,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'desired_salary' => 'required|integer',
            'notes' => 'required|string|max:65535',
        ];
    }

    /**
     * Create a candidate.
     *
     * @param array $data
     * @return Candidate
     */
    public function execute(array $data): Candidate
    {
        $this->data = $data;

        $this->validate();
        $this->create();

        return $this->candidate;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->jobOpening = JobOpening::findOrFail($this->data['job_opening_id']);
    }

    private function create(): void
    {
        $this->candidate = Candidate::create([
            'job_opening_id' => $this->data['job_opening_id'],
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'url' => $this->valueOrNull($this->data, 'url'),
            'desired_salary' => $this->valueOrNull($this->data, 'desired_salary'),
            'notes' => $this->valueOrNull($this->data, 'notes'),
            'uuid' => Str::uuid()->toString(),
        ]);
    }
}
