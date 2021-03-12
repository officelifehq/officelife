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
use App\Services\Company\Employee\Skill\RemoveSkillFromEmployee;

class RemoveSkillFromEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_a_skill_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $skill = Skill::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $skill->employees()->attach([$dwight->id]);

        $this->executeService($michael, $dwight, $skill);
    }

    /** @test */
    public function it_removes_a_skill_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $skill = Skill::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $skill->employees()->attach([$dwight->id]);

        $this->executeService($michael, $dwight, $skill);
    }

    /** @test */
    public function it_removes_a_skill_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $skill = Skill::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $skill->employees()->attach([$michael->id]);

        $this->executeService($michael, $michael, $skill);
    }

    /** @test */
    public function normal_user_cant_execute_the_service_against_another_user(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $skill = Skill::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $skill->employees()->attach([$dwight->id]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight, $skill);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new RemoveSkillFromEmployee)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_employee_is_not_in_the_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
        $skill = Skill::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight, $skill);
    }

    private function executeService(Employee $michael, Employee $dwight, Skill $skill): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'skill_id' => $skill->id,
        ];

        (new RemoveSkillFromEmployee)->execute($request);

        $this->assertDatabaseMissing('skills', [
            'id' => $skill->id,
        ]);

        $this->assertDatabaseMissing('employee_skill', [
            'skill_id' => $skill->id,
            'employee_id' => $dwight->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $skill, $dwight) {
            return $job->auditLog['action'] === 'skill_removed_from_an_employee' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'skill_id' => $skill->id,
                    'skill_name' => $skill->name,
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $skill) {
            return $job->auditLog['action'] === 'skill_removed_from_an_employee' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'skill_id' => $skill->id,
                    'skill_name' => $skill->name,
                ]);
        });
    }
}
