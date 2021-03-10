<?php

namespace Tests\Unit\ViewHelpers\Company\Project;

use Tests\TestCase;
use App\Helpers\DateHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Project;
use App\Models\Company\ProjectLink;
use App\Models\Company\ProjectStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\Project\ProjectViewHelper;

class ProjectViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_list_of_projects(): void
    {
        $michael = $this->createAdministrator();
        $projectA = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectB = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $array = ProjectViewHelper::index($michael->company);
        $this->assertEquals(2, count($array['projects']));

        $this->assertEquals(
            [
                0 => [
                    'id' => $projectB->id,
                    'name' => $projectB->name,
                    'code' => $projectB->code,
                    'status' => $projectB->status,
                    'summary' => $projectB->summary,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$projectB->id,
                ],
                1 => [
                    'id' => $projectA->id,
                    'name' => $projectA->name,
                    'code' => $projectA->code,
                    'status' => $projectA->status,
                    'summary' => $projectA->summary,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$projectA->id,
                ],
            ],
            $array['projects']->toArray()
        );
    }

    /** @test */
    public function it_gets_information_about_the_project_summary(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
            'project_lead_id' => $michael->id,
        ]);
        $link = ProjectLink::factory()->create([
            'project_id' => $project->id,
        ]);
        $status = ProjectStatus::factory()->create([
            'project_id' => $project->id,
        ]);

        $array = ProjectViewHelper::summary($project, $michael->company);

        $this->assertEquals(
            $project->id,
            $array['id']
        );
        $this->assertEquals(
            $project->name,
            $array['name']
        );
        $this->assertEquals(
            $project->name,
            $array['name']
        );
        $this->assertEquals(
            $project->code,
            $array['code']
        );
        $this->assertEquals(
            null,
            $array['summary']
        );
        $this->assertEquals(
            'created',
            $array['status']
        );
        $this->assertEquals(
            $project->description,
            $array['raw_description']
        );
        $this->assertEquals(
            '<p>'.$project->description.'</p>',
            $array['parsed_description']
        );
        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$project->id.'/edit',
            $array['url_edit']
        );
        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$project->id.'/delete',
            $array['url_delete']
        );
        $this->assertEquals(
            [
                'id' => $michael->id,
                'name' => $michael->name,
                'avatar' => $michael->avatar,
                'position' => [
                    'id' => $michael->position->id,
                    'title' => $michael->position->title,
                ],
                'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
            ],
            $array['project_lead']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $link->id,
                    'type' => $link->type,
                    'label' => $link->label,
                    'url' => $link->url,
                ],
            ],
            $array['links']->toArray()
        );
        $this->assertEquals(
            [
                'title' => $status->title,
                'status' => $status->status,
                'description' => StringHelper::parse($status->description),
                'written_at' => DateHelper::formatDate($status->created_at),
                'author' => [
                    'id' => $status->author->id,
                    'name' => $status->author->name,
                    'avatar' => $status->author->avatar,
                    'position' => (! $status->author->position) ? null : [
                        'id' => $status->author->position->id,
                        'title' => $status->author->position->title,
                    ],
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$status->author->id,
                ],
            ],
            $array['latest_update']
        );
    }

    /** @test */
    public function it_shows_information_to_edit_a_project(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $array = ProjectViewHelper::edit($project);

        $this->assertEquals(
            [
                'id' => $project->id,
                'name' => $project->name,
                'code' => $project->code,
                'summary' => null,
            ],
            $array
        );
    }

    /** @test */
    public function it_shows_information_about_the_permissions_of_the_logged_user(): void
    {
        $michael = $this->createEmployee();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        // employee is not part of the project
        $array = ProjectViewHelper::permissions($project, $michael);
        $this->assertEquals(
            [
                'can_edit_latest_update' => false,
                'can_manage_links' => false,
            ],
            $array
        );

        // employee is part of the project
        $project->employees()->syncWithoutDetaching([$michael->id]);
        $array = ProjectViewHelper::permissions($project, $michael);
        $this->assertEquals(
            [
                'can_edit_latest_update' => false,
                'can_manage_links' => true,
            ],
            $array
        );

        // employee is part of the project and is the project lead
        $project->project_lead_id = $michael->id;
        $project->save();
        $project->refresh();
        $array = ProjectViewHelper::permissions($project, $michael);
        $this->assertEquals(
            [
                'can_edit_latest_update' => true,
                'can_manage_links' => true,
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_information_about_the_project(): void
    {
        $michael = $this->createAdministrator();
        $jim = $this->createAnotherEmployee($michael);
        $tom = $this->createAnotherEmployee($michael);
        $pam = $this->createAnotherEmployee($michael);
        $jenny = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$michael->id]);
        $project->employees()->attach([$jim->id]);
        $project->employees()->attach([$tom->id]);
        $project->employees()->attach([$pam->id]);
        $project->employees()->attach([$jenny->id]);

        $array = ProjectViewHelper::info($project);

        $this->assertEquals(
            $project->id,
            $array['id']
        );
        $this->assertEquals(
            $project->name,
            $array['name']
        );
        $this->assertEquals(
            $project->code,
            $array['code']
        );
        $this->assertEquals(
            $project->summary,
            $array['summary']
        );
        $this->assertEquals(
            4,
            count($array['members']->toArray())
        );
        $this->assertEquals(
            1,
            $array['other_members_counter']
        );
    }
}
