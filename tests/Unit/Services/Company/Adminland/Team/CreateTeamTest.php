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
use App\Services\Company\Adminland\Team\CreateTeam;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_team_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael, 'product');
    }

    /** @test */
    public function it_creates_a_team_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael, 'product');
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $this->executeService($michael, 'product');
    }

    /** @test */
    public function it_cant_create_a_team_with_a_not_unique_name_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        factory(Team::class)->create([
            'company_id' => $michael->company_id,
            'name' => 'Product',
        ]);

        $this->expectException(TeamNameNotUniqueException::class);
        $this->executeService($michael, 'Product');

        $this->expectException(TeamNameNotUniqueException::class);
        $this->executeService($michael, 'product    ');
    }

    /** @test */
    public function it_can_create_a_team_with_a_name_already_taken_by_a_team_in_another_company(): void
    {
        $michael = $this->createAdministrator();
        factory(Team::class)->create([
            'name' => 'Product',
        ]);

        $this->executeService($michael, 'Product');
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new CreateTeam)->execute($request);
    }

    private function executeService(Employee $michael, string $name): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => $name,
        ];

        $sales = (new CreateTeam)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $sales->id,
            'company_id' => $michael->company_id,
            'name' => $name,
        ]);

        $this->assertInstanceOf(
            Team::class,
            $sales
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $sales) {
            return $job->auditLog['action'] === 'team_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_id' => $sales->id,
                    'team_name' => $sales->name,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $sales) {
            return $job->auditLog['action'] === 'team_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_id' => $sales->id,
                    'team_name' => $sales->name,
                ]);
        });
    }
}
