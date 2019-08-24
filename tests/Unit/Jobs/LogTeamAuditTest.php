<?php

namespace Tests\Unit\Jobs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogTeamAuditTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_a_team_audit(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $team->employees()->attach(
            $michael->id,
            [
                'company_id' => $michael->company_id,
                'created_at' => Carbon::now('UTC'),
            ]
        );

        $request = [
            'team_id' => $team->id,
            'action' => 'employee_status_created',
            'objects' => json_encode([
                'author_id' => $michael->id,
                'author_name' => $michael->name,
                'company_name' => $michael->company->name,
            ]),
        ];

        LogTeamAudit::dispatch($request);

        $this->assertDatabaseHas('team_logs', [
            'team_id' => $team->id,
            'action' => 'employee_status_created',
            'objects' => json_encode([
                'author_id' => $michael->id,
                'author_name' => $michael->name,
                'company_name' => $michael->company->name,
            ]),
        ]);
    }
}
