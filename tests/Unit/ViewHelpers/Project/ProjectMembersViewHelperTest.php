<?php

namespace Tests\Unit\ViewHelpers\Project;

use Tests\TestCase;
use App\Helpers\DateHelper;
use App\Models\Company\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Project\ProjectMembersViewHelper;

class ProjectMembersViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_employees(): void
    {
        $michael = $this->createAdministrator();
        $jim = $this->createAnotherEmployee($michael);
        $project = factory(Project::class)->create([
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

        $array = ProjectMembersViewHelper::members($project);
        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => $michael->avatar,
                    'role' => 'developer',
                    'added_at' => DateHelper::formatDate($michael->created_at),
                    'position' => [
                        'id' => $michael->position->id,
                        'title' => $michael->position->title,
                    ],
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
                1 => [
                    'id' => $jim->id,
                    'name' => $jim->name,
                    'avatar' => $jim->avatar,
                    'role' => 'ios dev',
                    'added_at' => DateHelper::formatDate($jim->created_at),
                    'position' => [
                        'id' => $jim->position->id,
                        'title' => $jim->position->title,
                    ],
                    'url' => env('APP_URL').'/'.$jim->company_id.'/employees/'.$jim->id,
                ],
            ],
            $array['members']->toArray()
        );
        $this->assertEquals(
            [
                '0' => [
                    'id' => $michael->id,
                    'role' => 'developer',
                ],
                1 => [
                    'id' => $jim->id,
                    'role' => 'ios dev',
                ],
            ],
            $array['roles']->toArray()
        );
    }

    /** @test */
    public function it_gets_information_about_the_project(): void
    {
        $michael = $this->createAdministrator();
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $array = ProjectMembersViewHelper::info($project);

        $this->assertEquals(
            [
                'id' => $project->id,
                'name' => $project->name,
                'code' => $project->code,
                'summary' => $project->summary,
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_a_collection_of_potential_members(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([
            $michael->id => [
                'role' => 'developer',
            ],
        ]);

        $array = ProjectMembersViewHelper::potentialMembers($project);
        $this->assertEquals(
            [
                0 => [
                    'value' => $dwight->id,
                    'label' => $dwight->name,
                ],
            ],
            $array['potential_members']->toArray()
        );
    }
}
