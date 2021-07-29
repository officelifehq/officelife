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
use App\Services\Company\Adminland\JobOpening\DestroyRecruitingStage;

class DestroyRecruitingStageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_a_recruiting_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $template = RecruitingStageTemplate::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $stage = RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $template->id,
            'position' => 2,
        ]);
        $this->executeService($michael, $template, $stage);
    }

    /** @test */
    public function it_deletes_a_recruiting_as_hr(): void
    {
        $michael = $this->createHR();
        $template = RecruitingStageTemplate::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $stage = RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $template->id,
            'position' => 2,
        ]);
        $this->executeService($michael, $template, $stage);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $template = RecruitingStageTemplate::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $stage = RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $template->id,
            'position' => 2,
        ]);
        $this->executeService($michael, $template, $stage);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyRecruitingStage)->execute($request);
    }

    /** @test */
    public function it_fails_if_template_doesnt_belong_to_company(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $michael = $this->createAdministrator();
        $template = RecruitingStageTemplate::factory()->create();
        $stage = RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $template->id,
            'position' => 1,
        ]);
        $this->executeService($michael, $template, $stage);
    }

    /** @test */
    public function it_fails_if_stage_doesnt_belong_to_company(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $michael = $this->createAdministrator();
        $template = RecruitingStageTemplate::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $stage = RecruitingStage::factory()->create([
            'position' => 1,
        ]);
        $this->executeService($michael, $template, $stage);
    }

    private function executeService(Employee $michael, RecruitingStageTemplate $template, RecruitingStage $stage): void
    {
        Queue::fake();

        $stage1 = RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $template->id,
            'position' => 1,
        ]);
        $stage3 = RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $template->id,
            'position' => 3,
        ]);
        $stage4 = RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $template->id,
            'position' => 4,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'recruiting_stage_template_id' => $template->id,
            'recruiting_stage_id' => $stage->id,
        ];

        (new DestroyRecruitingStage)->execute($request);

        $this->assertDatabaseMissing('recruiting_stages', [
            'id' => $stage->id,
            'recruiting_stage_template_id' => $template->id,
        ]);

        $stage1->refresh();
        $stage3->refresh();
        $stage4->refresh();

        $this->assertDatabaseHas('recruiting_stages', [
            'id' => $stage1->id,
            'recruiting_stage_template_id' => $template->id,
            'position' => 1,
        ]);
        $this->assertDatabaseHas('recruiting_stages', [
            'id' => $stage3->id,
            'recruiting_stage_template_id' => $template->id,
            'position' => 2,
        ]);
        $this->assertDatabaseHas('recruiting_stages', [
            'id' => $stage4->id,
            'recruiting_stage_template_id' => $template->id,
            'position' => 3,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $stage) {
            return $job->auditLog['action'] === 'recruiting_stage_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'recruiting_stage_name' => $stage->name,
                ]);
        });
    }
}
