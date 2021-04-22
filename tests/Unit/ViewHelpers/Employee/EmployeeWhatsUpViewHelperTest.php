<?php

namespace Tests\Unit\ViewHelpers\Employee;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use App\Models\Company\Project;
use App\Models\Company\OneOnOneEntry;
use App\Models\Company\ProjectMemberActivity;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Employee\EmployeeWhatsUpViewHelper;

class EmployeeWhatsUpViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_information_about_the_employee(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $startDate = Carbon::now()->subMonths(2);
        $endDate = Carbon::now()->addMonths(2);

        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        // two teams
        $sales = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $management = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $sales->employees()->attach([$dwight->id]);
        $management->employees()->attach([$dwight->id]);

        // one on ones
        OneOnOneEntry::factory()->count(2)->create([
            'employee_id' => $dwight->id,
            'happened_at' => Carbon::now(),
        ]);
        OneOnOneEntry::factory()->create([
            'employee_id' => $dwight->id,
            'happened_at' => Carbon::now()->subMonths(4),
        ]);

        // recent ships (Accomplishments)
        $ship = Ship::factory()->create([
            'team_id' => $sales->id,
            'created_at' => Carbon::now(),
        ]);
        $shipB = Ship::factory()->create([
            'team_id' => $sales->id,
            'created_at' => Carbon::now()->subMonths(4),
        ]);
        $ship->employees()->attach([$dwight->id]);
        $shipB->employees()->attach([$dwight->id]);

        // projects
        $projectA = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectB = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectA->employees()->attach([$dwight->id]);
        $projectB->employees()->attach([$dwight->id]);

        // project activity
        ProjectMemberActivity::factory()->create([
            'project_id' => $projectA->id,
            'employee_id' => $dwight->id,
            'created_at' => Carbon::now(),
        ]);
        ProjectMemberActivity::factory()->create([
            'project_id' => $projectB->id,
            'employee_id' => $dwight->id,
            'created_at' => Carbon::now()->subMonths(4),
        ]);

        $array = EmployeeWhatsUpViewHelper::index($dwight, Carbon::now(), Carbon::now()->addMonth(), $michael->company);

        $this->assertEquals(
            2,
            $array['one_on_ones_as_direct_report_count']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $ship->id,
                    'title' => $ship->title,
                    'description' => $ship->description,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/teams/'.$sales->id.'/ships/'.$ship->id,
                ],
            ],
            $array['recent_ships']->toArray()
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $projectA->id,
                    'name' => $projectA->name,
                    'code' => $projectA->code,
                    'summary' => $projectA->summary,
                    'status' => $projectA->status,
                    'status_i18n' => trans('project.summary_status_'.$projectA->status),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$projectA->id,
                ],
            ],
            $array['projects']->toArray()
        );
    }
}
