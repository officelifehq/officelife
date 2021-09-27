<?php

namespace Tests\Unit\ViewHelpers\Company\Project;

use Tests\TestCase;
use App\Models\Company\Project;
use App\Models\Company\ProjectBoard;
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
}
