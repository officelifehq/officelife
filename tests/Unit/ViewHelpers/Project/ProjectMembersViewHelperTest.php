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
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([
            $michael->id => [
                'role' => 'developer',
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
            ],
            $array['members']->toArray()
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
}
