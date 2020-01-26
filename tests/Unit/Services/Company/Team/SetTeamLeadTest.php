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
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SetTeamLeadTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_sets_someone_external_to_the_team_as_team_leader(): void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        $sales = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $this->assertDatabaseMissing('employee_team', [
            'employee_id' => $michael->id,
            'team_id' => $sales->id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'team_id' => $sales->id,
        ];

        $michael = (new SetTeamLead)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $sales->id,
            'team_leader_id' => $michael->id,
        ]);

        $this->assertDatabaseHas('employee_team', [
            'employee_id' => $michael->id,
            'team_id' => $sales->id,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $sales) {
            return $job->auditLog['action'] === 'team_leader_assigned' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_leader_id' => $michael->id,
                    'team_leader_name' => $michael->name,
                'team_name' => $sales->name,
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

        Queue::assertPushed(NotifyEmployee::class, function ($job) use ($sales, $michael) {
            return $job->notification['action'] === 'team_lead_set' &&
                $job->notification['employee_id'] === $michael->id &&
                $job->notification['objects'] === json_encode([
                    'team_name' => $sales->name,
                ]);
        });
    }

    /** @test */
    public function it_sets_someone_internal_to_the_team_as_team_leader(): void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        $sales = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $sales->employees()->attach(
            $michael->id,
            [
                'created_at' => Carbon::now('UTC'),
            ]
        );

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'team_id' => $sales->id,
        ];

        $michael = (new SetTeamLead)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $sales->id,
            'team_leader_id' => $michael->id,
        ]);

        $this->assertDatabaseHas('employee_team', [
            'employee_id' => $michael->id,
            'team_id' => $sales->id,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $sales) {
            return $job->auditLog['action'] === 'team_leader_assigned' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_leader_id' => $michael->id,
                    'team_leader_name' => $michael->name,
                    'team_name' => $sales->name,
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
}
