<?php

namespace Tests\Unit\Models\Company;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use App\Models\Company\Project;
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
        $sales = Team::factory()->create([]);
        $this->assertTrue($sales->company()->exists());
    }

    /** @test */
    public function it_has_many_employees()
    {
        $sales = Team::factory()->create([]);
        $dwight = Employee::factory()->create([
            'company_id' => $sales->company_id,
        ]);
        $michael = Employee::factory()->create([
            'company_id' => $sales->company_id,
        ]);

        $sales->employees()->syncWithoutDetaching([$dwight->id]);
        $sales->employees()->syncWithoutDetaching([$michael->id]);

        $this->assertTrue($sales->employees()->exists());
    }

    /** @test */
    public function it_has_many_ships()
    {
        $sales = Team::factory()->create([]);
        Ship::factory()->count(2)->create([
            'team_id' => $sales->id,
        ]);

        $this->assertTrue($sales->ships()->exists());
    }

    /** @test */
    public function it_has_a_leader()
    {
        $sales = Team::factory()->create([
            'team_leader_id' => Employee::factory(),
        ]);
        $this->assertTrue($sales->leader()->exists());
    }

    /** @test */
    public function it_has_many_links()
    {
        $sales = Team::factory()->create([]);
        TeamUsefulLink::factory()->count(2)->create([
            'team_id' => $sales->id,
        ]);

        $this->assertTrue($sales->links()->exists());
    }

    /** @test */
    public function it_has_many_news()
    {
        $sales = Team::factory()->create([]);
        TeamNews::factory()->count(2)->create([
            'team_id' => $sales->id,
        ]);

        $this->assertTrue($sales->news()->exists());
    }

    /** @test */
    public function it_has_many_projects()
    {
        $sales = Team::factory()->create([]);
        $apiv3 = Project::factory()->create([
            'company_id' => $sales->company_id,
        ]);
        $apiv4 = Project::factory()->create([
            'company_id' => $sales->company_id,
        ]);

        $sales->projects()->syncWithoutDetaching([$apiv3->id]);
        $sales->projects()->syncWithoutDetaching([$apiv4->id]);

        $this->assertTrue($sales->projects()->exists());
    }

    /** @test */
    public function it_returns_the_number_of_team_members_who_have_completed_a_worklog_at_a_given_time()
    {
        $date = Carbon::now();
        $sales = Team::factory()->create([]);
        $dwight = Employee::factory()->create([
            'company_id' => $sales->company_id,
        ]);
        $michael = Employee::factory()->create([
            'company_id' => $sales->company_id,
        ]);

        $sales->employees()->syncWithoutDetaching([$dwight->id]);
        $sales->employees()->syncWithoutDetaching([$michael->id]);

        Worklog::factory()->create([
            'employee_id' => $dwight->id,
            'created_at' => $date,
        ]);
        Worklog::factory()->create([
            'employee_id' => $michael->id,
            'created_at' => $date,
        ]);

        $this->assertEquals(
            2,
            count($sales->worklogsForDate($date))
        );
    }
}
