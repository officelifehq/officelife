<?php

namespace Tests\Unit\Services\Company\Adminland\JobOpening;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\JobOpening;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\JobOpening\ToggleJobOpening;

class ToggleJobOpeningTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_toggles_the_job_opening_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $jobOpening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
            'active' => false,
        ]);
        $this->executeService($michael, $jobOpening);
    }

    /** @test */
    public function it_toggles_the_job_opening_as_hr(): void
    {
        $michael = $this->createHR();
        $jobOpening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
            'active' => false,
        ]);
        $this->executeService($michael, $jobOpening);
    }

    /** @test */
    public function normal_user_cant_call_the_service(): void
    {
        $michael = $this->createEmployee();
        $jobOpening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
            'active' => false,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $jobOpening);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new ToggleJobOpening)->execute($request);
    }

    protected function executeService(Employee $author, JobOpening $opening): void
    {
        Queue::fake();

        $request = [
            'company_id' => $author->company_id,
            'author_id' => $author->id,
            'job_opening_id' => $opening->id,
        ];

        $opening = (new ToggleJobOpening)->execute($request);

        $this->assertInstanceOf(
            JobOpening::class,
            $opening
        );

        $this->assertDatabaseHas('job_openings', [
            'id' => $opening->id,
            'active' => true,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($author, $opening) {
            return $job->auditLog['action'] === 'job_opening_toggled' &&
                $job->auditLog['author_id'] === $author->id &&
                $job->auditLog['objects'] === json_encode([
                    'job_opening_id' => $opening->id,
                    'job_opening_title' => $opening->title,
                ]);
        });
    }
}
