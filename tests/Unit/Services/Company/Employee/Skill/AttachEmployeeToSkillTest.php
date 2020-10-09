<?php

namespace Tests\Unit\Services\Company\Employee\Skill;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Skill;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Skill\AttachEmployeeToSkill;

class AttachEmployeeToSkillTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_assigns_a_skill_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight, 'PéÔ', 'peo');
    }

    /** @test */
    public function it_creates_a_new_skill_and_assigns_it_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $skill = factory(Skill::class)->create([
            'company_id' => $michael->company_id,
            'name' => 'peo',
        ]);
        $this->executeService($michael, $dwight, 'PéÔ', 'peo', $skill);
    }

    /** @test */
    public function it_assigns_a_skill_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight, 'PéÔ', 'peo');
    }

    /** @test */
    public function it_assigns_a_skill_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael, $michael, 'PéÔ', 'peo');
    }

    /** @test */
    public function normal_user_cant_execute_the_service_against_another_user(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight, 'PéÔ', 'peo');
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new AttachEmployeeToSkill)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_employee_is_not_in_the_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, 'PéÔ', 'peo');
    }

    private function executeService(Employee $michael, Employee $dwight, string $skillName, string $name, Skill $skillAlreadyExisting = null): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'name' => $skillName,
        ];

        $skill = (new AttachEmployeeToSkill)->execute($request);

        $this->assertDatabaseHas('skills', [
            'id' => $skill->id,
            'company_id' => $dwight->company_id,
            'name' => $name,
        ]);

        $this->assertDatabaseHas('employee_skill', [
            'skill_id' => $skill->id,
            'employee_id' => $dwight->id,
        ]);

        $this->assertInstanceOf(
            Skill::class,
            $skill
        );

        // if the skill doesn't alreaady exist, it will create it.
        if (! $skillAlreadyExisting) {
            Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $skill, $dwight) {
                return $job->auditLog['action'] === 'skill_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'skill_id' => $skill->id,
                    'skill_name' => $skill->name,
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                ]);
            });
        } else {
            $this->assertEquals(
                $skill->id,
                $skillAlreadyExisting->id
            );
        }

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $skill, $dwight) {
            return $job->auditLog['action'] === 'skill_associated_with_employee' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'skill_id' => $skill->id,
                    'skill_name' => $skill->name,
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $skill) {
            return $job->auditLog['action'] === 'skill_associated_with_employee' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'skill_id' => $skill->id,
                    'skill_name' => $skill->name,
                ]);
        });
    }
}
