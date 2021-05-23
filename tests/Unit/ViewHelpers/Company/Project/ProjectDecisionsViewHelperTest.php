<?php

namespace Tests\Unit\ViewHelpers\Company\Project;

use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Employee;
use App\Models\Company\ProjectDecision;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\Project\ProjectDecisionsViewHelper;

class ProjectDecisionsViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_decisions(): void
    {
        $projectDecision = ProjectDecision::factory()->create([]);
        $michael = Employee::factory()->create([
            'company_id' => $projectDecision->project->company_id,
        ]);
        $jim = Employee::factory()->create([
            'company_id' => $projectDecision->project->company_id,
        ]);
        $projectDecision->deciders()->attach([$michael->id]);
        $projectDecision->deciders()->attach([$jim->id]);
        $collection = ProjectDecisionsViewHelper::decisions($projectDecision->project, $michael);

        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael, 22),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
                1 => [
                    'id' => $jim->id,
                    'name' => $jim->name,
                    'avatar' => ImageHelper::getAvatar($jim, 22),
                    'url' => env('APP_URL').'/'.$jim->company_id.'/employees/'.$jim->id,
                ],
            ],
            $collection->toArray()[0]['deciders']->toArray()
        );
        $this->assertEquals(
            $projectDecision->id,
            $collection->toArray()[0]['id']
        );
        $this->assertEquals(
            $projectDecision->title,
            $collection->toArray()[0]['title']
        );
    }

    /** @test */
    public function it_searches_employees_to_assign_a_project_lead(): void
    {
        $michael = Employee::factory()->create([
            'first_name' => 'ale',
            'last_name' => 'ble',
            'email' => 'ale@ble',
        ]);
        $dwight = Employee::factory()->create([
            'first_name' => 'alb',
            'last_name' => 'bli',
            'email' => 'alb@bli',
            'company_id' => $michael->company_id,
        ]);
        // the following should not be included in the search results
        Employee::factory()->create([
            'first_name' => 'ale',
            'last_name' => 'ble',
            'email' => 'ale@ble',
            'locked' => true,
            'company_id' => $michael->company_id,
        ]);

        $collection = ProjectDecisionsViewHelper::searchDeciders($michael->company, 'e');
        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael, 23),
                ],
            ],
            $collection->toArray()
        );

        $collection = ProjectDecisionsViewHelper::searchDeciders($michael->company, 'bli');
        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $dwight->id,
                    'name' => $dwight->name,
                    'avatar' => ImageHelper::getAvatar($dwight, 23),
                ],
            ],
            $collection->toArray()
        );
    }
}
