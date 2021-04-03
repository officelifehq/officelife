<?php

namespace Tests\Unit\Services\Company\Employee\Skill;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Skill;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use App\Services\Company\Employee\Skill\DestroySkill;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroySkillTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_a_skill_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $skill = Skill::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $skill);
    }

    /** @test */
    public function it_deletes_a_skill_as_hr(): void
    {
        $michael = $this->createHR();
        $skill = Skill::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $skill);
    }

    /** @test */
    public function it_cant_delete_the_skill_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $skill = Skill::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $skill);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'This is my answer',
        ];

        $this->expectException(ValidationException::class);
        (new DestroySkill)->execute($request);
    }

    /** @test */
    public function it_fails_if_skill_is_not_linked_to_company(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $michael = $this->createAdministrator();
        $skill = Skill::factory()->create([]);

        $this->executeService($michael, $skill);
    }

    private function executeService(Employee $michael, Skill $skill): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'skill_id' => $skill->id,
        ];

        (new DestroySkill)->execute($request);

        $this->assertDatabaseMissing('skills', [
            'id' => $skill->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $skill) {
            return $job->auditLog['action'] === 'skill_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'skill_name' => $skill->name,
                ]);
        });
    }
}
