<?php

namespace Tests\Unit\Services\Company\Adminland\JobOpening;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\RecruitingStage;
use Illuminate\Validation\ValidationException;
use App\Models\Company\RecruitingStageTemplate;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\JobOpening\CreateRecruitingStage;

class CreateRecruitingStageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_recruiting_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $template = RecruitingStageTemplate::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $template);
    }

    /** @test */
    public function it_creates_a_recruiting_as_hr(): void
    {
        $michael = $this->createHR();
        $template = RecruitingStageTemplate::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $template);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $template = RecruitingStageTemplate::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $template);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new CreateRecruitingStage)->execute($request);
    }

    /** @test */
    public function it_fails_if_template_is_not_linked_to_the_company(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $michael = $this->createAdministrator();
        $template = RecruitingStageTemplate::factory()->create();

        $this->executeService($michael, $template);
    }

    private function executeService(Employee $michael, RecruitingStageTemplate $template): void
    {
        Queue::fake();

        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $template->id,
            'position' => 8,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'recruiting_stage_template_id' => $template->id,
            'name' => 'Interview with 2 devs',
        ];

        $stage = (new CreateRecruitingStage)->execute($request);

        $this->assertDatabaseHas('recruiting_stages', [
            'id' => $stage->id,
            'recruiting_stage_template_id' => $template->id,
            'name' => 'Interview with 2 devs',
            'position' => 9,
        ]);

        $this->assertInstanceOf(
            RecruitingStage::class,
            $stage
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $stage) {
            return $job->auditLog['action'] === 'recruiting_stage_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'recruiting_stage_id' => $stage->id,
                    'recruiting_stage_name' => $stage->name,
                ]);
        });
    }
}
