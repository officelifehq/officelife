<?php

namespace Tests\Unit\ViewHelpers\Company\Project;

use Tests\TestCase;
use App\Models\Company\Company;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\IssueType;
use App\Models\Company\ProjectBoard;
use App\Models\Company\ProjectIssue;
use App\Models\Company\ProjectSprint;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\Project\ProjectBoardsViewHelper;

class ProjectBoardsViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_boards(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $board = ProjectBoard::factory()->create([
            'project_id' => $project->id,
        ]);

        $array = ProjectBoardsViewHelper::index($project);

        $this->assertEquals(
            [
                0 => [
                    'id' => $board->id,
                    'name' => $board->name,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$project->id.'/boards/'.$board->id,
                ],
            ],
            $array['boards']->toArray()
        );

        $this->assertEquals(
            [
                'store' => env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$project->id.'/boards',
            ],
            $array['url']
        );
    }

    /** @test */
    public function it_gets_the_details_of_the_board(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $board = ProjectBoard::factory()->create([
            'project_id' => $project->id,
        ]);

        $array = ProjectBoardsViewHelper::show($project, $board);

        $this->assertEquals(
            [
                'id' => $board->id,
                'name' => $board->name,
            ],
            $array['data']
        );

        $this->assertEquals(
            [],
            $array['url']
        );
    }

    /** @test */
    public function it_gets_a_collection_of_issue_types(): void
    {
        $company = Company::factory()->create();
        $issueType = IssueType::factory()->create([
            'company_id' => $company->id,
        ]);

        $collection = ProjectBoardsViewHelper::issueTypes($company);

        $this->assertEquals(
            [
                0 => [
                    'id' => $issueType->id,
                    'name' => $issueType->name,
                    'icon_hex_color' => $issueType->icon_hex_color,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_information_about_the_backlog(): void
    {
        $company = Company::factory()->create();
        $michael = Employee::factory()->create([
            'company_id' => $company->id,
        ]);
        $project = Project::factory()->create([
            'company_id' => $company->id,
        ]);
        $projectBoard = ProjectBoard::factory()->create([
            'project_id' => $project->id,
        ]);
        $backlog = ProjectSprint::factory()->create([
            'project_board_id' => $projectBoard->id,
            'name' => 'Backlog',
            'is_board_backlog' => true,
        ]);
        $otherSprint = ProjectSprint::factory()->create([
            'project_board_id' => $projectBoard->id,
            'is_board_backlog' => false,
        ]);
        for ($i = 0; $i < 3; $i++) {
            $issue = ProjectIssue::factory()->create([
                'project_id' => $project->id,
                'project_board_id' => $projectBoard->id,
            ]);
            $backlog->issues()->syncWithoutDetaching($issue->id);
            $otherSprint->issues()->syncWithoutDetaching($issue->id);
        }

        $array = ProjectBoardsViewHelper::backlog($project, $projectBoard, $michael);

        $this->assertEquals(
            [
                'id' => $projectBoard->id,
                'name' => $projectBoard->name,
            ],
            $array['board']
        );

        $this->assertEquals(
            2,
            $array['sprints']->count()
        );
    }
}
