<?php

namespace Tests\Unit\ViewHelpers\Company\Project;

use Tests\TestCase;
use App\Helpers\DateHelper;
use App\Models\Company\Company;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectBoard;
use App\Models\Company\ProjectIssue;
use App\Models\Company\ProjectSprint;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\Project\ProjectSprintsViewHelper;

class ProjectSprintsViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_information_about_the_sprint(): void
    {
        $company = Company::factory()->create();
        $michael = Employee::factory()->create(['company_id' => $company->id]);
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
        $issue = ProjectIssue::factory()->create([
            'project_id' => $project->id,
            'project_board_id' => $projectBoard->id,
        ]);
        $backlog->issues()->syncWithoutDetaching($issue->id);

        $array = ProjectSprintsViewHelper::sprintData($project, $projectBoard, $backlog, $michael);

        $this->assertEquals(
            7,
            count($array)
        );

        $this->assertEquals(
            $backlog->id,
            $array['id']
        );
        $this->assertEquals(
            'Backlog',
            $array['name']
        );
        $this->assertEquals(
            true,
            $array['active']
        );
        $this->assertEquals(
            false,
            $array['collapsed']
        );
        $this->assertEquals(
            true,
            $array['is_board_backlog']
        );
        $this->assertEquals(
            [
                'store' => env('APP_URL').'/'.$company->id. '/company/projects/'.$project->id.'/boards/'.$projectBoard->id.'/sprints/'.$backlog->id.'/issues',
                'toggle' => env('APP_URL').'/'.$company->id. '/company/projects/'.$project->id.'/boards/'.$projectBoard->id.'/sprints/'.$backlog->id.'/toggle',
            ],
            $array['url']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $issue->id,
                    'key' => $issue->key,
                    'title' => $issue->title,
                    'slug' => $issue->slug,
                    'is_separator' => false,
                    'created_at' => DateHelper::formatMonthAndDay($issue->created_at),
                    'story_points' => $issue->story_points,
                    'type' => $issue->type ? [
                        'name' => $issue->type->name,
                        'icon_hex_color' => $issue->type->icon_hex_color,
                    ] : null,
                    'url' => [
                        'show' => env('APP_URL').'/'.$company->id.'/issues/'.$issue->key.'/'.$issue->slug,
                        'reorder' => env('APP_URL').'/'.$company->id.'/company/projects/'.$project->id.'/boards/'.$projectBoard->id.'/sprints/'.$backlog->id.'/issues/'.$issue->id.'/order',
                        'destroy' => env('APP_URL').'/'.$company->id.'/company/projects/'.$project->id.'/boards/'.$projectBoard->id.'/sprints/'.$backlog->id.'/issues/'.$issue->id,
                    ],
                ],
            ],
            $array['issues']->toArray()
        );
    }
}
