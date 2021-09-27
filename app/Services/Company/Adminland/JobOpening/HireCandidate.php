<?php

namespace App\Services\Company\Adminland\JobOpening;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use App\Services\Company\Employee\Team\AddEmployeeToTeam;
use App\Services\Company\Employee\HiringDate\SetHiringDate;
use App\Services\Company\Adminland\Employee\AddEmployeeToCompany;

class HireCandidate extends BaseService
{
    protected array $data;
    protected JobOpening $jobOpening;
    protected Candidate $candidate;
    protected Employee $employee;
    protected Carbon $hiredAt;

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
            'email' => 'required|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'hired_at' => 'required|date_format:Y-m-d',
        ];
    }

    /**
     * Hire a candidate:
     * - create the employee,
     * - mark the job opening as fulfilled
     * - deactivate the job opening.
     *
     * @param array $data
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->data = $data;
        $this->validate();
        $this->createEmployee();
        $this->markJobOpeningAsFulfilled();
        $this->updateCandidate();
        $this->log();

        return $this->employee;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->jobOpening = JobOpening::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['job_opening_id']);

        $this->candidate = Candidate::where('company_id', $this->data['company_id'])
            ->where('job_opening_id', $this->data['job_opening_id'])
            ->findOrFail($this->data['candidate_id']);

        $this->hiredAt = Carbon::createFromFormat('Y-m-d', $this->data['hired_at']);
    }

    private function createEmployee(): void
    {
        $this->employee = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->jobOpening->company_id,
            'author_id' => $this->author->id,
            'email' => $this->data['email'],
            'first_name' => $this->data['first_name'],
            'last_name' => $this->data['last_name'],
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);

        (new SetHiringDate)->execute([
            'company_id' => $this->jobOpening->company_id,
            'author_id' => $this->author->id,
            'employee_id' => $this->employee->id,
            'year' => $this->hiredAt->year,
            'month' => $this->hiredAt->month,
            'day' => $this->hiredAt->day,
        ]);

        $team = $this->jobOpening->team;
        if ($team) {
            (new AddEmployeeToTeam)->execute([
                'company_id' => $this->jobOpening->company_id,
                'author_id' => $this->author->id,
                'employee_id' => $this->employee->id,
                'team_id' => $team->id,
            ]);
        }

        $this->employee->position_id = $this->jobOpening->position_id;
        $this->employee->save();
    }

    private function markJobOpeningAsFulfilled(): void
    {
        $this->jobOpening->active = false;
        $this->jobOpening->fulfilled = true;
        $this->jobOpening->fulfilled_at = Carbon::now();
        $this->jobOpening->fulfilled_by_candidate_id = $this->candidate->id;
        $this->jobOpening->save();
    }

    private function updateCandidate(): void
    {
        $this->candidate->employee_id = $this->employee->id;
        $this->candidate->employee_name = $this->employee->name;
        $this->candidate->save();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'candidate_hired',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'job_opening_id' => $this->jobOpening->id,
                'job_opening_title' => $this->jobOpening->title,
                'job_opening_reference_number' => $this->jobOpening->reference_number,
                'candidate_id' => $this->candidate->id,
                'candidate_name' => $this->candidate->name,
            ]),
        ])->onQueue('low');
    }
}
