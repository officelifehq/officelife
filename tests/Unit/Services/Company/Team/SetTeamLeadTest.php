<?php

namespace Tests\Unit\Services\Company\Team;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Jobs\NotifyEmployee;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Services\Company\Team\SetTeamLead;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SetTeamLeadTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_sets_someone_a_team_lead_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $this->executeService($michael, $team, false);
        $this->executeService($michael, $team, true);
    }

    /** @test */
    public function it_sets_someone_a_team_lead_as_hr(): void
    {
        $michael = $this->createHR();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $this->executeService($michael, $team, false);
        $this->executeService($michael, $team, true);
    }

    /** @test */
    public function normal_user_cant_execute_service(): void
    {
        $michael = $this->createEmployee();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $team, true);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $team, false);
    }

    /** @test */
    public function it_fails_if_the_team_is_not_part_of_the_company(): void
    {
        $michael = $this->createAdministrator();
        $team = factory(Team::class)->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $team, false);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = factory(Employee::class)->create([]);
        factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
        ];

        $this->expectException(ValidationException::class);
        (new SetTeamLead)->execute($request);
    }

    private function executeService(Employee $michael, Team $team, $isInternal): void
    {
        Queue::fake();

        if ($isInternal) {
            $team->employees()->attach(
                $michael->id,
                [
                    'created_at' => Carbon::now('UTC'),
                ]
            );
        }

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'team_id' => $team->id,
        ];

        $michael = (new SetTeamLead)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'team_leader_id' => $michael->id,
        ]);

        $this->assertDatabaseHas('employee_team', [
            'employee_id' => $michael->id,
            'team_id' => $team->id,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $team) {
            return $job->auditLog['action'] === 'team_leader_assigned' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_leader_id' => $michael->id,
                    'team_leader_name' => $michael->name,
                    'team_name' => $team->name,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'team_leader_assigned' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_leader_id' => $michael->id,
                    'team_leader_name' => $michael->name,
                ]);
        });

        Queue::assertPushed(NotifyEmployee::class, function ($job) use ($team, $michael) {
            return $job->notification['action'] === 'team_lead_set' &&
                $job->notification['employee_id'] === $michael->id &&
                $job->notification['objects'] === json_encode([
                    'team_name' => $team->name,
                ]);
        });
    }
}
