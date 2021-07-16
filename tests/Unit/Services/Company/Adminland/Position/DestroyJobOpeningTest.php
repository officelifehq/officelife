<?php

namespace Tests\Unit\Services\Company\Adminland\Position;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\JobOpening;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\JobOpening\DestroyJobOpening;

class DestroyJobOpeningTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_job_opening_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $jobOpening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $jobOpening);
    }

    /** @test */
    public function it_destroys_a_job_opening_as_hr(): void
    {
        $michael = $this->createHR();
        $jobOpening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $jobOpening);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $jobOpening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $jobOpening);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyJobOpening)->execute($request);
    }

    /** @test */
    public function it_fails_if_job_opening_is_not_linked_to_company(): void
    {
        $jobOpening = JobOpening::factory()->create([]);
        $michael = $this->createAdministrator();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $jobOpening);
    }

    private function executeService(Employee $michael, JobOpening $jobOpening): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'job_opening_id' => $jobOpening->id,
        ];

        (new DestroyJobOpening)->execute($request);

        $this->assertDatabaseMissing('job_openings', [
            'id' => $jobOpening->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $jobOpening) {
            return $job->auditLog['action'] === 'job_opening_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'job_opening_title' => $jobOpening->title,
                ]);
        });
    }
}
