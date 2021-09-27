<?php

namespace Tests\Unit\Services\Company\Adminland\JobOpening;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\JobOpening;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Models\Company\RecruitingStageTemplate;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\JobOpening\UpdateJobOpening;

class UpdateJobOpeningTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_job_opening_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $jobOpening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $sponsor = Employee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $jobOpening->sponsors()->syncWithoutDetaching([
            $sponsor->id,
        ]);
        $position = Position::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $template = RecruitingStageTemplate::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($jobOpening, $michael, $michael, $position, $template, $team);
    }

    /** @test */
    public function it_updates_a_job_opening_as_hr(): void
    {
        $michael = $this->createHR();
        $jobOpening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $sponsor = Employee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $jobOpening->sponsors()->syncWithoutDetaching([
            $sponsor->id,
        ]);
        $position = Position::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $template = RecruitingStageTemplate::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($jobOpening, $michael, $michael, $position, $template, $team);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $jobOpening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $sponsor = Employee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $jobOpening->sponsors()->syncWithoutDetaching([
            $sponsor->id,
        ]);
        $position = Position::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $template = RecruitingStageTemplate::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($jobOpening, $michael, $michael, $position, $template, $team);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateJobOpening)->execute($request);
    }

    /** @test */
    public function it_fails_if_job_position_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $jobOpening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $sponsor = Employee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $jobOpening->sponsors()->syncWithoutDetaching([
            $sponsor->id,
        ]);
        $position = Position::factory()->create([]);
        $template = RecruitingStageTemplate::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($jobOpening, $michael, $michael, $position, $template, $team);
    }

    /** @test */
    public function it_fails_if_team_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $jobOpening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $sponsor = Employee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $jobOpening->sponsors()->syncWithoutDetaching([
            $sponsor->id,
        ]);
        $position = Position::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $template = RecruitingStageTemplate::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $team = Team::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($jobOpening, $michael, $michael, $position, $template, $team);
    }

    /** @test */
    public function it_fails_if_template_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $jobOpening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $sponsor = Employee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $jobOpening->sponsors()->syncWithoutDetaching([
            $sponsor->id,
        ]);
        $position = Position::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $template = RecruitingStageTemplate::factory()->create();
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->expectException(ModelNotFoundException::class);
        $this->executeService($jobOpening, $michael, $michael, $position, $template, $team);
    }

    /** @test */
    public function it_fails_if_job_opening_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $jobOpening = JobOpening::factory()->create();
        $sponsor = Employee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $jobOpening->sponsors()->syncWithoutDetaching([
            $sponsor->id,
        ]);
        $position = Position::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $template = RecruitingStageTemplate::factory()->create();
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->expectException(ModelNotFoundException::class);
        $this->executeService($jobOpening, $michael, $michael, $position, $template, $team);
    }

    private function executeService(JobOpening $jobOpening, Employee $author, Employee $sponsor, Position $position, RecruitingStageTemplate $template, Team $team): void
    {
        Queue::fake();

        $request = [
            'company_id' => $author->company_id,
            'author_id' => $author->id,
            'job_opening_id' => $jobOpening->id,
            'position_id' => $position->id,
            'sponsors' => [$sponsor->id],
            'team_id' => $team->id,
            'recruiting_stage_template_id' => $template->id,
            'title' => 'Assistant to the regional manager',
            'description' => 'Awesome job',
            'reference_number' => '123',
        ];

        $jobOpening = (new UpdateJobOpening)->execute($request);

        $this->assertDatabaseHas('job_openings', [
            'id' => $jobOpening->id,
            'company_id' => $author->company_id,
            'position_id' => $position->id,
            'recruiting_stage_template_id' => $template->id,
            'title' => 'Assistant to the regional manager',
            'description' => 'Awesome job',
            'reference_number' => '123',
        ]);

        $this->assertDatabaseHas('job_opening_sponsor', [
            'job_opening_id' => $jobOpening->id,
            'employee_id' => $sponsor->id,
        ]);

        $this->assertInstanceOf(
            JobOpening::class,
            $jobOpening
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($author, $jobOpening) {
            return $job->auditLog['action'] === 'job_opening_updated' &&
                $job->auditLog['author_id'] === $author->id &&
                $job->auditLog['objects'] === json_encode([
                    'job_opening_id' => $jobOpening->id,
                    'job_opening_title' => $jobOpening->title,
                ]);
        });
    }
}
