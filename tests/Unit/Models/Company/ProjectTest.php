<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectLink;
use App\Models\Company\ProjectStatus;
use App\Models\Company\ProjectMessage;
use App\Models\Company\ProjectDecision;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $project = factory(Project::class)->create([]);
        $this->assertTrue($project->company()->exists());
    }

    /** @test */
    public function it_has_one_lead(): void
    {
        $dwight = factory(Employee::class)->create();
        $project = factory(Project::class)->create([
            'company_id' => $dwight->company_id,
            'project_lead_id' => $dwight->id,
        ]);

        $this->assertTrue($project->lead()->exists());
    }

    /** @test */
    public function it_belongs_to_many_employees(): void
    {
        $project = factory(Project::class)->create();
        $dwight = factory(Employee::class)->create([
            'company_id' => $project->company_id,
        ]);
        $michael = factory(Employee::class)->create([
            'company_id' => $project->company_id,
        ]);

        $project->employees()->syncWithoutDetaching([$dwight->id]);
        $project->employees()->syncWithoutDetaching([$michael->id]);

        $this->assertTrue($project->employees()->exists());
    }

    /** @test */
    public function it_belongs_to_many_teams(): void
    {
        $project = factory(Project::class)->create();
        $sales = factory(Team::class)->create([
            'company_id' => $project->company_id,
        ]);
        $marketing = factory(Team::class)->create([
            'company_id' => $project->company_id,
        ]);

        $project->teams()->syncWithoutDetaching([$sales->id]);
        $project->teams()->syncWithoutDetaching([$marketing->id]);

        $this->assertTrue($project->teams()->exists());
    }

    /** @test */
    public function it_has_many_links(): void
    {
        $project = factory(Project::class)->create();
        factory(ProjectLink::class, 2)->create([
            'project_id' => $project->id,
        ]);

        $this->assertTrue($project->links()->exists());
    }

    /** @test */
    public function it_has_many_statuses(): void
    {
        $project = factory(Project::class)->create();
        factory(ProjectStatus::class, 2)->create([
            'project_id' => $project->id,
        ]);

        $this->assertTrue($project->statuses()->exists());
    }

    /** @test */
    public function it_has_many_decisions(): void
    {
        $project = factory(Project::class)->create();
        factory(ProjectDecision::class, 2)->create([
            'project_id' => $project->id,
        ]);

        $this->assertTrue($project->decisions()->exists());
    }

    /** @test */
    public function it_has_many_messages(): void
    {
        $project = factory(Project::class)->create();
        factory(ProjectMessage::class, 2)->create([
            'project_id' => $project->id,
        ]);

        $this->assertTrue($project->messages()->exists());
    }
}
