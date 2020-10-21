<?php

namespace Tests\Unit\ViewHelpers\Project;

use Tests\TestCase;
use App\Models\Company\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Project\ProjectDecisionsViewHelper;

class ProjectDecisionViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_information_about_the_project(): void
    {
        $michael = $this->createAdministrator();
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $array = ProjectDecisionsViewHelper::info($project);

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
}
