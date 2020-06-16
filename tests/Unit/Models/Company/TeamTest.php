<?php

namespace Tests\Unit\Models\Company;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Ship;
use App\Models\Company\Task;
use App\Models\Company\Team;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use App\Models\Company\TeamNews;
use App\Models\Company\TeamUsefulLink;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_company()
    {
        $sales = factory(Team::class)->create([]);
        $this->assertTrue($sales->company()->exists());
    }

    /** @test */
    public function it_has_many_employees()
    {
        $sales = factory(Team::class)->create([]);
        $dwight = factory(Employee::class)->create([
            'company_id' => $sales->company_id,
        ]);
        $michael = factory(Employee::class)->create([
            'company_id' => $sales->company_id,
        ]);

        $sales->employees()->syncWithoutDetaching([$dwight->id]);
        $sales->employees()->syncWithoutDetaching([$michael->id]);

        $this->assertTrue($sales->employees()->exists());
    }

    /** @test */
    public function it_has_many_ships()
    {
        $sales = factory(Team::class)->create([]);
        factory(Ship::class, 2)->create([
            'team_id' => $sales->id,
        ]);

        $this->assertTrue($sales->ships()->exists());
    }

    /** @test */
    public function it_has_a_leader()
    {
        $sales = factory(Team::class)->create([]);
        $this->assertTrue($sales->leader()->exists());
    }

    /** @test */
    public function it_has_many_tasks()
    {
        $sales = factory(Team::class)->create([]);
        factory(Task::class, 2)->create([
            'team_id' => $sales->id,
        ]);

        $this->assertTrue($sales->tasks()->exists());
    }

    /** @test */
    public function it_has_many_links()
    {
        $sales = factory(Team::class)->create([]);
        factory(TeamUsefulLink::class, 2)->create([
            'team_id' => $sales->id,
        ]);

        $this->assertTrue($sales->links()->exists());
    }

    /** @test */
    public function it_has_many_news()
    {
        $sales = factory(Team::class)->create([]);
        factory(TeamNews::class, 2)->create([
            'team_id' => $sales->id,
        ]);

        $this->assertTrue($sales->news()->exists());
    }

    /** @test */
    public function it_returns_the_number_of_team_members_who_have_completed_a_worklog_at_a_given_time()
    {
        $date = Carbon::now();
        $sales = factory(Team::class)->create([]);
        $dwight = factory(Employee::class)->create([
            'company_id' => $sales->company_id,
        ]);
        $michael = factory(Employee::class)->create([
            'company_id' => $sales->company_id,
        ]);

        $sales->employees()->syncWithoutDetaching([$dwight->id]);
        $sales->employees()->syncWithoutDetaching([$michael->id]);

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
            count($sales->worklogsForDate($date))
        );
    }
}
