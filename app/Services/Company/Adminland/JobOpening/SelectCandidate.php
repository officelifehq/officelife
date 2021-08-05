<?php

namespace App\Services\Company\Adminland\JobOpening;

use Illuminate\Support\Str;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use Illuminate\Support\Facades\DB;

class SelectCandidate extends BaseService
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
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'job_opening_id' => 'required|integer|exists:job_openings,id',
            'candidate_id' => 'required|integer|exists:candidates,id',
        ];
    }

    /**
     * Select a candidate, allowing her/him to enter the recruitment process.
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

        // check if the author is a sponsor
        $isSponsor = DB::table('job_opening_sponsor')
            ->where('employee_id', $this->data['author_id'])
            ->exists();

        if (! $isSponsor) {
            $this->author = Employee::where('company_id', $this->data['company_id'])
                ->where('permission_level', '<=', config('officelife.permission_level.hr'))
                ->firstOrFail();
        }

        $this->jobOpening = JobOpening::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['job_opening_id']);

        $this->candidate = JobOpening::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['job_opening_id']);
    }

    private function create(): void
    {
        $this->candidate = Candidate::create([
            'company_id' => $this->data['company_id'],
            'job_opening_id' => $this->data['job_opening_id'],
            'name' => $this->data['name'],
            'status' => Candidate::STATUS_TO_SORT,
            'email' => $this->data['email'],
            'url' => $this->valueOrNull($this->data, 'url'),
            'desired_salary' => $this->valueOrNull($this->data, 'desired_salary'),
            'notes' => $this->valueOrNull($this->data, 'notes'),
            'uuid' => Str::uuid()->toString(),
        ]);
    }
}
