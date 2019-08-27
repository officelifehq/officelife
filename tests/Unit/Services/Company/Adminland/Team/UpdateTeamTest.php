<?php

namespace Tests\Unit\Services\Company\Adminland\Team;

use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Adminland\Team\UpdateTeam;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_team() : void
    {
        Queue::fake();

        $team = factory(Team::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $team->company_id,
        ]);

        $request = [
            'company_id' => $team->company_id,
            'author_id' => $michael->id,
            'team_id' => $team->id,
            'name' => 'Selling team',
        ];

        (new UpdateTeam)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'company_id' => $team->company_id,
            'name' => 'Selling team',
        ]);

        $this->assertInstanceOf(
            Team::class,
            $team
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $team) {
            return $job->auditLog['action'] === 'team_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_id' => $team->id,
                    'team_old_name' => $team->name,
                    'team_new_name' => 'Selling team',
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $team) {
            return $job->auditLog['action'] === 'team_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_id' => $team->id,
                    'team_old_name' => $team->name,
                    'team_new_name' => 'Selling team',
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateTeam)->execute($request);
    }
}
