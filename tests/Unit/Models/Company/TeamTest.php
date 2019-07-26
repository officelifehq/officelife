<?php

namespace Tests\Unit\Models\Company;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Task;
use App\Models\Company\Team;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_company()
    {
        $team = factory(Team::class)->create([]);
        $this->assertTrue($team->company()->exists());
    }

    /** @test */
    public function it_has_many_employees()
    {
        $team = factory(Team::class)->create([]);
        $dwight = factory(Employee::class)->create([
            'company_id' => $team->company_id,
        ]);
        $michael = factory(Employee::class)->create([
            'company_id' => $team->company_id,
        ]);

        $team->employees()->syncWithoutDetaching([$dwight->id => ['company_id' => $team->company_id]]);
        $team->employees()->syncWithoutDetaching([$michael->id => ['company_id' => $team->company_id]]);

        $this->assertTrue($team->employees()->exists());
    }

    /** @test */
    public function it_has_a_leader()
    {
        $team = factory(Team::class)->create([]);
        $this->assertTrue($team->leader()->exists());
    }

    /** @test */
    public function it_has_many_tasks()
    {
        $team = factory(Team::class)->create([]);
        factory(Task::class, 2)->create([
            'team_id' => $team->id,
        ]);

        $this->assertTrue($team->tasks()->exists());
    }

    /** @test */
    public function it_returns_the_number_of_team_members_who_have_completed_a_worklog_at_a_given_time()
    {
        $date = Carbon::now();
        $team = factory(Team::class)->create([]);
        $dwight = factory(Employee::class)->create([
            'company_id' => $team->company_id,
        ]);
        $michael = factory(Employee::class)->create([
            'company_id' => $team->company_id,
        ]);

        $team->employees()->syncWithoutDetaching([$dwight->id => ['company_id' => $team->company_id]]);
        $team->employees()->syncWithoutDetaching([$michael->id => ['company_id' => $team->company_id]]);

        factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => $date,
        ]);
        factory(Worklog::class)->create([
            'employee_id' => $michael->id,
            'created_at' => $date,
        ]);

        $this->assertEquals(
            2,
            count($team->worklogsForDate($date))
        );
    }
}
