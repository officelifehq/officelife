<?php

namespace Tests\Unit\Services\Company\Adminland\Team;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use App\Services\Company\Adminland\Team\DestroyTeam;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_team_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_destroys_a_team_as_hr(): void
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
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyTeam)->execute($request);
    }

    /** @test */
    public function it_fails_if_team_doesnt_belong_to_the_company(): void
    {
        $michael = $this->createAdministrator();
        $sales = Team::factory()->create([]);

        $request = [
            'company_id' => $sales->company_id,
            'author_id' => $michael->id,
            'team_id' => $sales->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new DestroyTeam)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $team->company_id,
            'author_id' => $michael->id,
            'team_id' => $team->id,
        ];

        (new DestroyTeam)->execute($request);

        $this->assertDatabaseMissing('teams', [
            'id' => $team->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $team) {
            return $job->auditLog['action'] === 'team_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_name' => $team->name,
                ]);
        });
    }
}
