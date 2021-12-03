<?php

namespace Tests\Unit\ViewHelpers\Company\Project;

use Carbon\Carbon;
use Tests\TestCase;
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
        ]);

        $array = ProjectIssuesViewHelper::issueData($issue);

        $this->assertEquals(
            8,
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
            'Jan 01, 2018',
            $array['created_at']
        );
        $this->assertEquals(
            $issue->story_points,
            $array['story_points']
        );
        $this->assertEquals(
            [
                'name' => $issue->type->name,
                'icon_hex_color' => $issue->type->icon_hex_color,
            ],
            $array['type']
        );
    }
}
