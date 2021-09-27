<?php

namespace Tests\Unit\Services\Company\Adminland\JobOpening;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\CompanyPTOPolicy;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\JobOpening\HireCandidate;

class HireCandidateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_hires_a_candidate_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);

        $this->executeService($michael, $opening, $candidate);
    }

    /** @test */
    public function it_hires_a_candidate_as_hr(): void
    {
        $michael = $this->createHR();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);

        $this->executeService($michael, $opening, $candidate);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);

        $this->executeService($michael, $opening, $candidate);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new HireCandidate)->execute($request);
    }

    /** @test */
    public function it_fails_if_job_opening_doesnt_belong_to_company(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $michael = $this->createAdministrator();
        $opening = JobOpening::factory()->create([]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);

        $this->executeService($michael, $opening, $candidate);
    }

    private function executeService(Employee $author, JobOpening $opening, Candidate $candidate): void
    {
        Queue::fake();
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        // used to populate the holidays
        CompanyPTOPolicy::factory()->create([
            'company_id' => $author->company_id,
            'year' => 2018,
        ]);

        $employee = (new HireCandidate)->execute([
            'company_id' => $author->company_id,
            'author_id' => $author->id,
            'job_opening_id' => $opening->id,
            'candidate_id' => $candidate->id,
            'email' => 'michael@dundermifflin.com',
            'first_name' => 'Michael',
            'last_name' => 'Scott v2',
            'hired_at' => '2022-01-01',
        ]);

        $this->assertDatabaseHas('job_openings', [
            'id' => $opening->id,
            'fulfilled_by_candidate_id' => $candidate->id,
            'fulfilled_at' => '2018-01-01 00:00:00',
            'active' => false,
            'fulfilled' => true,
        ]);

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'company_id' => $employee->company_id,
            'first_name' => 'Michael',
            'last_name' => 'Scott v2',
            'hired_at' => '2022-01-01 00:00:00',
            'position_id' => $opening->position_id,
        ]);

        $this->assertDatabaseHas('employee_team', [
            'employee_id' => $employee->id,
            'team_id' => $opening->team_id,
        ]);

        $this->assertDatabaseHas('candidates', [
            'id' => $candidate->id,
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $employee
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($author, $opening, $candidate) {
            return $job->auditLog['action'] === 'candidate_hired' &&
                $job->auditLog['author_id'] === $author->id &&
                $job->auditLog['objects'] === json_encode([
                    'job_opening_id' => $opening->id,
                    'job_opening_title' => $opening->title,
                    'job_opening_reference_number' => $opening->reference_number,
                    'candidate_id' => $candidate->id,
                    'candidate_name' => $candidate->name,
                ]);
        });
    }
}
