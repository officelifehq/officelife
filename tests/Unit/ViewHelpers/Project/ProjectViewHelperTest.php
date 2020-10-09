<?php

namespace Tests\Unit\ViewHelpers\Project;

use Tests\TestCase;
use App\Models\Company\Project;
use App\Http\ViewHelpers\Project\ProjectViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_list_of_projects(): void
    {
        $michael = $this->createAdministrator();
        $projectA = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $projectB = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $array = ProjectViewHelper::index($michael->company);
        $this->assertEquals(2, count($array['projects']));

        $this->assertEquals(
            [
                0 => [
                    'id' => $projectA->id,
                    'name' => $projectA->name,
                    'code' => $projectA->code,
                    'summary' => $projectA->summary,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/projects/'.$projectA->id,
                ],
                1 => [
                    'id' => $projectB->id,
                    'name' => $projectB->name,
                    'code' => $projectB->code,
                    'summary' => $projectB->summary,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/projects/'.$projectB->id,
                ],
            ],
            $array['projects']->toArray()
        );
    }

    /** @test */
    public function it_gets_information_about_the_project_summary(): void
    {
        $michael = $this->createAdministrator();
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
            'project_lead_id' => $michael->id,
        ]);

        $array = ProjectViewHelper::summary($project, $michael->company);

        $this->assertEquals(
            [
                'name' => 'API v3',
                'code' => $project->code,
                'summary' => null,
                'status' => 'created',
                'parsed_description' => '<p>it is going well</p>',
                'url_edit' => env('APP_URL').'/'.$michael->company_id.'/projects/'.$project->id.'/edit',
                'url_delete' => env('APP_URL').'/'.$michael->company_id.'/projects/'.$project->id.'/delete',
                'project_lead' => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => $michael->avatar,
                    'position' => [
                        'id' => $michael->position->id,
                        'title' => $michael->position->title,
                    ],
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
            ],
            $array
        );
    }

    /** @test */
    public function it_shows_information_to_edit_a_project(): void
    {
        $michael = $this->createAdministrator();
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $array = ProjectViewHelper::edit($project);

        $this->assertEquals(
            [
                'id' => $project->id,
                'name' => 'API v3',
                'code' => $project->code,
                'summary' => null,
            ],
            $array
        );
    }
}
