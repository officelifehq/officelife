<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\File;
use App\Models\Company\Team;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectLink;
use App\Models\Company\ProjectTask;
use App\Models\Company\ProjectBoard;
use App\Models\Company\ProjectStatus;
use App\Models\Company\ProjectMessage;
use App\Models\Company\ProjectDecision;
use App\Models\Company\ProjectTaskList;
use App\Models\Company\TimeTrackingEntry;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $project = Project::factory()->create();
        $this->assertTrue($project->company()->exists());
    }

    /** @test */
    public function it_has_one_lead(): void
    {
        $dwight = Employee::factory()->create();
        $project = Project::factory()->create([
            'company_id' => $dwight->company_id,
            'project_lead_id' => $dwight->id,
        ]);

        $this->assertTrue($project->lead()->exists());
    }

    /** @test */
    public function it_belongs_to_many_employees(): void
    {
        $project = Project::factory()->create();
        $dwight = Employee::factory()->create([
            'company_id' => $project->company_id,
        ]);
        $michael = Employee::factory()->create([
            'company_id' => $project->company_id,
        ]);

        $project->employees()->syncWithoutDetaching([$dwight->id]);
        $project->employees()->syncWithoutDetaching([$michael->id]);

        $this->assertTrue($project->employees()->exists());
    }

    /** @test */
    public function it_belongs_to_many_teams(): void
    {
        $project = Project::factory()->create();
        $sales = Team::factory()->create([
            'company_id' => $project->company_id,
        ]);
        $marketing = Team::factory()->create([
            'company_id' => $project->company_id,
        ]);

        $project->teams()->syncWithoutDetaching([$sales->id]);
        $project->teams()->syncWithoutDetaching([$marketing->id]);

        $this->assertTrue($project->teams()->exists());
    }

    /** @test */
    public function it_has_many_links(): void
    {
        $project = Project::factory()->create();
        ProjectLink::factory()->count(2)->create([
            'project_id' => $project->id,
        ]);

        $this->assertTrue($project->links()->exists());
    }

    /** @test */
    public function it_has_many_statuses(): void
    {
        $project = Project::factory()->create();
        ProjectStatus::factory()->count(2)->create([
            'project_id' => $project->id,
        ]);

        $this->assertTrue($project->statuses()->exists());
    }

    /** @test */
    public function it_has_many_decisions(): void
    {
        $project = Project::factory()->create();
        ProjectDecision::factory()->count(2)->create([
            'project_id' => $project->id,
        ]);

        $this->assertTrue($project->decisions()->exists());
    }

    /** @test */
    public function it_has_many_messages(): void
    {
        $project = Project::factory()->create();
        ProjectMessage::factory()->count(2)->create([
            'project_id' => $project->id,
        ]);

        $this->assertTrue($project->messages()->exists());
    }

    /** @test */
    public function it_has_many_tasks(): void
    {
        $project = Project::factory()
            ->has(ProjectTask::factory()->count(2), 'tasks')
            ->create();

        $this->assertTrue($project->tasks()->exists());
    }

    /** @test */
    public function it_has_many_task_lists(): void
    {
        $project = Project::factory()
            ->has(ProjectTaskList::factory()->count(2), 'lists')
            ->create();

        $this->assertTrue($project->lists()->exists());
    }

    /** @test */
    public function it_has_many_time_tracking_entries(): void
    {
        $project = Project::factory()
            ->has(TimeTrackingEntry::factory()->count(2), 'timeTrackingEntries')
            ->create();

        $this->assertTrue($project->timeTrackingEntries()->exists());
    }

    /** @test */
    public function it_has_many_files(): void
    {
        $project = Project::factory()
            ->create();

        $file = File::factory()->create();
        $project->files()->sync([$file->id]);

        $this->assertTrue($project->files()->exists());
    }

    /** @test */
    public function it_has_many_time_project_boards(): void
    {
        $project = Project::factory()->create();
        ProjectBoard::factory()->count(2)->create([
            'project_id' => $project->id,
        ]);

        $this->assertTrue($project->boards()->exists());
    }
}
