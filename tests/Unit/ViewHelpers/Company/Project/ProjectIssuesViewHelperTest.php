<?php

namespace Tests\Unit\ViewHelpers\Company\Project;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Project;
use App\Models\Company\ProjectBoard;
use App\Models\Company\ProjectIssue;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\Project\ProjectIssuesViewHelper;

class ProjectIssuesViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_details_of_the_issue(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $board = ProjectBoard::factory()->create([
            'project_id' => $project->id,
        ]);
        $issue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
            'project_board_id' => $board->id,
            'description' => 'this is a description',
        ]);
        $issue->assignees()->syncWithoutDetaching([$michael->id]);

        $array = ProjectIssuesViewHelper::issueData($issue);

        $this->assertEquals(
            13,
            count($array)
        );
        $this->assertEquals(
            $issue->id,
            $array['id']
        );
        $this->assertEquals(
            $issue->key,
            $array['key']
        );
        $this->assertEquals(
            $issue->title,
            $array['title']
        );
        $this->assertEquals(
            $issue->slug,
            $array['slug']
        );
        $this->assertEquals(
            'this is a description',
            $array['description']
        );
        $this->assertEquals(
            '<p>this is a description</p>',
            $array['parsed_description']
        );
        $this->assertEquals(
            'Jan 01, 2018',
            $array['created_at']
        );
        $this->assertEquals(
            [
                'points' => $issue->story_points,
                'url' => [
                    'store' => env('APP_URL').'/'.$issue->project->company_id.'/company/projects/'.$issue->project->id.'/boards/'.$issue->board->id. '/issues/'.$issue->id.'/points',
                ],
            ],
            $array['story_points']
        );
        $this->assertEquals(
            [
                'name' => $issue->type->name,
                'icon_hex_color' => $issue->type->icon_hex_color,
            ],
            $array['type']
        );
        $this->assertEquals(
            [
                'id' => $issue->reporter->id,
                'name' => $issue->reporter->name,
                'avatar' => ImageHelper::getAvatar($issue->reporter, 25),
                'url' => env('APP_URL').'/'.$issue->reporter->company_id.'/employees/'.$issue->reporter->id,
            ],
            $array['author']
        );
        $this->assertEquals(
            [
                'id' => $issue->project->id,
                'name' => $issue->project->name,
                'url' => env('APP_URL').'/'.$issue->project->company_id.'/company/projects/'.$issue->project->id.'/boards',
            ],
            $array['project']
        );
        $this->assertEquals(
            [
                'index' => env('APP_URL').'/'.$issue->project->company_id.'/company/projects/'.$issue->project->id.'/boards/'.$issue->board->id.'/members',
                'store' => env('APP_URL').'/'.$issue->project->company_id.'/company/projects/'.$issue->project->id.'/boards/'.$issue->board->id. '/issues/'.$issue->id.'/assignees',
            ],
            $array['assignees']['url']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael, 25),
                    'url' => [
                        'show' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                        'destroy' => env('APP_URL').'/'.$issue->project->company_id.'/company/projects/'.$issue->project->id.'/boards/'.$issue->board->id. '/issues/'.$issue->id.'/assignees/'.$michael->id,
                    ],
                ],
            ],
            $array['assignees']['data']->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_employees(): void
    {
        $michael = $this->createAdministrator();
        $jim = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([
            $michael->id => [
                'role' => 'developer',
            ],
        ]);
        $project->employees()->attach([
            $jim->id => [
                'role' => 'ios dev',
            ],
        ]);

        $collection = ProjectIssuesViewHelper::members($project);
        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael),
                ],
                1 => [
                    'id' => $jim->id,
                    'name' => $jim->name,
                    'avatar' => ImageHelper::getAvatar($jim),
                ],
            ],
            $collection->toArray()
        );
    }
}
