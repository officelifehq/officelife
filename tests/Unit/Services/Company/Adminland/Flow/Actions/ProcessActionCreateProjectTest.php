<?php

namespace Tests\Unit\Services\Company\Adminland\Flow\Actions;

use Tests\TestCase;
use App\Models\Company\Project;
use App\Exceptions\MissingInformationInJsonAction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Flow\Actions\ProcessActionCreateProject;

class ProcessActionCreateProjectTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_projectz(): void
    {
        $michael = $this->createAdministrator();
        $project = (new ProcessActionCreateProject)->execute([
            'employee' => $michael,
            'content' => json_encode([
                'project_name' => 'Onboarding of employee x',
                'project_summary' => 'All the things we need to do to make sure',
                'project_description' => null,
                'project_project_lead_id' => null,
            ]),
        ]);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'company_id' => $michael->company_id,
            'project_lead_id' => null,
            'name' => 'Onboarding of employee x',
            'summary' => 'All the things we need to do to make sure',
            'description' => null,
        ]);

        $this->assertInstanceOf(
            Project::class,
            $project
        );
    }

    /** @test */
    public function it_fails_if_json_doesnt_contain_right_information(): void
    {
        $michael = $this->createAdministrator();
        $this->expectException(MissingInformationInJsonAction::class);

        (new ProcessActionCreateProject)->execute([
            'employee' => $michael,
            'content' => json_encode(['name' => 'Create a new project']),
        ]);
    }
}
