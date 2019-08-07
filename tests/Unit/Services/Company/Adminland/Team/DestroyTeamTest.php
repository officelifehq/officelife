<?php

namespace Tests\Unit\Services\Company\Adminland\Team;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use App\Jobs\Logs\LogAccountAudit;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Adminland\Team\DestroyTeam;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DestroyTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_team() : void
    {
        Queue::fake();

        $team = factory(Team::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $team->company_id,
        ]);

        $request = [
            'company_id' => $team->company_id,
            'author_id' => $michael->user->id,
            'team_id' => $team->id,
        ];

        (new DestroyTeam)->execute($request);

        $this->assertDatabaseMissing('teams', [
            'id' => $team->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $team) {
            return $job->auditLog['action'] === 'team_destroyed' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'team_name' => $team->name,
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
        (new DestroyTeam)->execute($request);
    }
}
