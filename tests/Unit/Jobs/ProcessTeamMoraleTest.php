<?php

namespace Tests\Unit\Jobs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Morale;
use App\Jobs\ProcessTeamMorale;
use App\Models\Company\Employee;
use App\Models\Company\MoraleTeamHistory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProcessTeamMoraleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_the_statistics_about_how_the_team_feels() :void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $team->employees()->attach(
            $dwight->id,
            [
                'company_id' => $dwight->company_id,
                'created_at' => Carbon::now('UTC'),
            ]
        );

        $team->employees()->attach(
            $michael->id,
            [
                'company_id' => $michael->company_id,
                'created_at' => Carbon::now('UTC'),
            ]
        );

        factory(Morale::class)->create([
            'employee_id' => $michael->id,
            'emotion' => 1,
        ]);

        factory(Morale::class)->create([
            'employee_id' => $dwight->id,
            'emotion' => 3,
        ]);

        $request = [
            'team_id' => $team->id,
            'date' => Carbon::now(),
        ];

        ProcessTeamMorale::dispatch($request);

        $this->assertEquals(
            1,
            MoraleTeamHistory::get()->count()
        );

        $this->assertDatabaseHas('morale_team_history', [
            'team_id' => $team->id,
            'average' => 2,
            'number_of_team_members' => 2,
        ]);

        ProcessTeamMorale::dispatch($request);

        $this->assertEquals(
            1,
            MoraleTeamHistory::get()->count()
        );
    }
}
