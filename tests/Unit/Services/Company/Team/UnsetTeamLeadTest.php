<?php

namespace Tests\Unit\Services\Company\Team;

use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Jobs\NotifyEmployee;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Services\Company\Team\UnsetTeamLead;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UnsetTeamLeadTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_resets_a_team_leader(): void
    {
        Queue::fake();

        $michael = Employee::factory()->asHR()->create();
        $sales = Team::factory()->create([
            'team_leader_id' => $michael->id,
            'company_id' => $michael->company_id,
        ]);

        $teamLeader = $sales->leader;

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $sales->leader->id,
            'team_id' => $sales->id,
        ];

        $sales = (new UnsetTeamLead)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $sales->id,
            'team_leader_id' => null,
        ]);

        $this->assertInstanceOf(
            Team::class,
            $sales
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($sales, $teamLeader) {
            return $job->auditLog['action'] === 'team_leader_removed' &&
                $job->auditLog['author_id'] === $teamLeader->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_leader_name' => $teamLeader->name,
                    'team_name' => $sales->name,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($teamLeader) {
            return $job->auditLog['action'] === 'team_leader_removed' &&
                $job->auditLog['author_id'] === $teamLeader->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_leader_name' => $teamLeader->name,
                ]);
        });

        Queue::assertPushed(NotifyEmployee::class, function ($job) use ($sales, $teamLeader) {
            return $job->notification['action'] === 'team_lead_removed' &&
                $job->notification['employee_id'] === $teamLeader->id &&
                $job->notification['objects'] === json_encode([
                    'team_name' => $sales->name,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ];

        $this->expectException(ValidationException::class);
        (new UnsetTeamLead)->execute($request);
    }
}
