<?php

namespace Tests\Unit\ViewHelpers\Project;

use Tests\TestCase;
use App\Helpers\DateHelper;
use App\Models\Company\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Project\ProjectDecisionsViewHelper;

class ProjectDecisionsViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_decisions(): void
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

        $array = ProjectDecisionsViewHelper::decisions($project);
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
}
