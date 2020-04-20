<?php

namespace Tests\Unit\Services\Company\Adminland\Team;

use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Exceptions\TeamNameNotUniqueException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use App\Services\Company\Adminland\Team\UpdateTeam;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_team_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael, 'sales', 'commerce');
    }

    /** @test */
    public function it_updates_a_team_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael, 'sales', 'commerce');
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);
        $michael = $this->createEmployee();
        $this->executeService($michael, 'sales', 'commerce');
    }

    /** @test */
    public function it_cant_update_a_team_with_a_not_unique_name_in_the_company(): void
    {
        $this->expectException(TeamNameNotUniqueException::class);
        $michael = $this->createAdministrator();

        factory(Team::class)->create([
            'company_id' => $michael->company_id,
            'name' => 'commerce',
        ]);

        $this->executeService($michael, 'sales', 'commerce');
    }

    /** @test */
    public function it_can_update_a_team_with_a_name_already_taken_by_a_team_in_another_company(): void
    {
        factory(Team::class)->create([
            'name' => 'Sales Team',
        ]);
        $michael = $this->createAdministrator();
        $this->executeService($michael, 'sales', 'Sales Team');
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateTeam)->execute($request);
    }

    /** @test */
    public function it_fails_if_team_doesnt_belong_to_the_company(): void
    {
        $michael = $this->createAdministrator();
        $sales = factory(Team::class)->create([]);

        $request = [
            'company_id' => $sales->company_id,
            'author_id' => $michael->id,
            'team_id' => $sales->id,
            'name' => 'sales',
        ];

        $this->expectException(ModelNotFoundException::class);
        (new UpdateTeam)->execute($request);
    }

    private function executeService(Employee $michael, string $currentName, string $newName): void
    {
        Queue::fake();

        $sales = factory(Team::class)->create([
            'company_id' => $michael->company_id,
            'name' => $currentName,
        ]);

        $request = [
            'company_id' => $sales->company_id,
            'author_id' => $michael->id,
            'team_id' => $sales->id,
            'name' => $newName,
        ];

        (new UpdateTeam)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $sales->id,
            'company_id' => $sales->company_id,
            'name' => $newName,
        ]);

        $this->assertInstanceOf(
            Team::class,
            $sales
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $sales, $newName) {
            return $job->auditLog['action'] === 'team_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_id' => $sales->id,
                    'team_old_name' => $sales->name,
                    'team_new_name' => $newName,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $sales, $newName) {
            return $job->auditLog['action'] === 'team_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_old_name' => $sales->name,
                    'team_new_name' => $newName,
                ]);
        });
    }
}
