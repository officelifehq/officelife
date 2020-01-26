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
        $date = Carbon::now();

        $michael = $this->createAdministrator();
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
            'team_id' => $sales->id,
            'action' => 'employee_status_created',
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'audited_at' => $date,
            'objects' => json_encode([
                'company_name' => $michael->company->name,
            ]),
        ];

        LogTeamAudit::dispatch($request);

        $this->assertDatabaseHas('team_logs', [
            'team_id' => $sales->id,
            'action' => 'employee_status_created',
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'audited_at' => $date,
            'objects' => json_encode([
                'company_name' => $michael->company->name,
            ]),
        ]);
    }
}
