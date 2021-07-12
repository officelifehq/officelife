<?php

namespace App\Services\Company\Adminland\JobOpening;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\JobOpening;

class CreateJobOpening extends BaseService
{
    protected array $data;
    protected Position $position;
    protected JobOpening $jobOpening;
    protected Employee $sponsor;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'position_id' => 'required|integer|exists:positions,id',
            'author_id' => 'required|integer|exists:employees,id',
            'sponsored_by_employee_id' => 'required|integer|exists:employees,id',
            'title' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'description' => 'required|string|max:65535',
        ];
    }

    /**
     * Create a job opening.
     *
     * @param array $data
     * @return JobOpening
     */
    public function execute(array $data): JobOpening
    {
        $this->data = $data;

        $this->validate();
        $this->create();
        $this->log();

        return $this->jobOpening;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->position = Position::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['position_id']);

        $this->sponsor = Employee::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['sponsored_by_employee_id']);
    }

    private function create(): void
    {
        $this->jobOpening = JobOpening::create([
            'company_id' => $this->data['company_id'],
            'position_id' => $this->data['position_id'],
            'sponsored_by_employee_id' => $this->data['sponsored_by_employee_id'],
            'title' => $this->data['title'],
            'description' => $this->data['description'],
            'reference_number' => $this->valueOrNull($this->data, 'reference_number'),
            'slug' => Str::slug($this->data['title'], '-'),
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'job_opening_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'job_opening_id' => $this->jobOpening->id,
                'job_opening_title' => $this->jobOpening->title,
            ]),
        ])->onQueue('low');
    }
}
