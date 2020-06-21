<?php

namespace Tests\Unit\Services\Company\Employee\Skill;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Skill;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\SkillNameNotUniqueException;
use App\Exceptions\NotEnoughPermissionException;
use App\Services\Company\Employee\Skill\UpdateSkill;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateSkillTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_skill_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael, 'php', 'jira');
    }

    /** @test */
    public function it_updates_a_skill_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael, 'php', 'jira');
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);
        $michael = $this->createEmployee();
        $this->executeService($michael, 'php', 'jira');
    }

    /** @test */
    public function it_cant_update_a_skill_with_a_not_unique_name_in_the_company(): void
    {
        $this->expectException(SkillNameNotUniqueException::class);
        $michael = $this->createAdministrator();

        factory(Team::class)->create([
            'company_id' => $michael->company_id,
            'name' => 'jira',
        ]);

        $this->executeService($michael, 'jira', 'jira');
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateSkill)->execute($request);
    }

    /** @test */
    public function it_fails_if_skill_doesnt_belong_to_the_company(): void
    {
        $michael = $this->createAdministrator();
        $skill = factory(Skill::class)->create([]);

        $request = [
            'company_id' => $skill->company_id,
            'author_id' => $michael->id,
            'skill_id' => $skill->id,
            'name' => 'skill',
        ];

        $this->expectException(ModelNotFoundException::class);
        (new UpdateSkill)->execute($request);
    }

    private function executeService(Employee $michael, string $currentName, string $newName): void
    {
        Queue::fake();

        $skill = factory(Skill::class)->create([
            'company_id' => $michael->company_id,
            'name' => $currentName,
        ]);

        $request = [
            'company_id' => $skill->company_id,
            'author_id' => $michael->id,
            'skill_id' => $skill->id,
            'name' => $newName,
        ];

        $skill = (new UpdateSkill)->execute($request);

        $this->assertDatabaseHas('skills', [
            'id' => $skill->id,
            'company_id' => $skill->company_id,
            'name' => $newName,
        ]);

        $this->assertInstanceOf(
            Skill::class,
            $skill
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $skill, $currentName, $newName) {
            return $job->auditLog['action'] === 'skill_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'skill_id' => $skill->id,
                    'skill_old_name' => $currentName,
                    'skill_new_name' => $newName,
                ]);
        });
    }
}
