<?php

namespace Tests\Unit\Services\Company\Adminland\JobOpening;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Models\Company\RecruitingStageTemplate;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\JobOpening\CreateRecruitingStageTemplate;

class CreateRecruitingStageTemplateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_recruiting_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_recruiting_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new CreateRecruitingStageTemplate)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'Interview with 2 devs',
        ];

        $template = (new CreateRecruitingStageTemplate)->execute($request);

        $this->assertDatabaseHas('recruiting_stage_templates', [
            'id' => $template->id,
            'company_id' => $michael->company_id,
            'name' => 'Interview with 2 devs',
        ]);

        $this->assertInstanceOf(
            RecruitingStageTemplate::class,
            $template
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $template) {
            return $job->auditLog['action'] === 'recruiting_stage_template_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'recruiting_stage_template_id' => $template->id,
                    'recruiting_stage_template_name' => $template->name,
                ]);
        });
    }
}
