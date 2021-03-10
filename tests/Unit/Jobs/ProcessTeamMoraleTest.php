<?php

namespace Tests\Unit\Jobs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Morale;
use App\Jobs\ProcessTeamMorale;
use App\Models\Company\MoraleTeamHistory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProcessTeamMoraleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_the_statistics_about_how_the_team_feels(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $sales = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $dwight = $this->createAnotherEmployee($michael);

        $sales->employees()->attach(
            $dwight->id,
            [
                'created_at' => Carbon::now('UTC'),
            ]
        );

        $sales->employees()->attach(
            $michael->id,
            [
                'created_at' => Carbon::now('UTC'),
            ]
        );

        Morale::factory()->create([
            'employee_id' => $michael->id,
            'emotion' => 1,
        ]);

        Morale::factory()->create([
            'employee_id' => $dwight->id,
            'emotion' => 3,
        ]);

        $request = [
            'team_id' => $sales->id,
            'date' => Carbon::now(),
        ];

        ProcessTeamMorale::dispatch($request);

        $this->assertEquals(
            1,
            MoraleTeamHistory::count()
        );

        $this->assertDatabaseHas('morale_team_history', [
            'team_id' => $sales->id,
            'average' => 2,
            'number_of_team_members' => 2,
        ]);

        ProcessTeamMorale::dispatch($request);

        $this->assertEquals(
            1,
            MoraleTeamHistory::count()
        );
    }
}
