<?php

namespace Tests\Unit\Services\Company\Adminland\Flow\Actions;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\ScheduledAction;
use App\Exceptions\MissingInformationInJsonAction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Flow\Actions\ProcessActionCreateTask;

class ProcessActionCreateTaskTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_task(): void
    {
        $michael = $this->createAdministrator();
        $scheduledAction = ScheduledAction::factory()->create([
            'employee_id' => $michael->id,
            'content' => json_encode(['task_name' => 'Create a new project']),
        ]);

        (new ProcessActionCreateTask)->execute($scheduledAction);

        $this->assertDatabaseHas('tasks', [
            'employee_id' => $michael->id,
            'title' => 'Create a new project',
        ]);
    }

    /** @test */
    public function it_fails_if_json_doesnt_contain_right_information(): void
    {
        $michael = $this->createAdministrator();
        $scheduledAction = ScheduledAction::factory()->create([
            'employee_id' => $michael->id,
            'content' => json_encode(['name' => 'Create a new project']),
        ]);

        $this->expectException(MissingInformationInJsonAction::class);
        (new ProcessActionCreateTask)->execute($scheduledAction);
    }

    /** @test */
    public function it_fails_if_employee_is_locked(): void
    {
        $michael = Employee::factory()->create([
            'locked' => true,
        ]);
        $scheduledAction = ScheduledAction::factory()->create([
            'employee_id' => $michael->id,
            'content' => json_encode(['task_name' => 'Create a new project']),
        ]);

        (new ProcessActionCreateTask)->execute($scheduledAction);

        $this->assertDatabaseMissing('tasks', [
            'employee_id' => $michael->id,
            'title' => 'Create a new project',
        ]);
    }

    /** @test */
    public function it_fails_if_action_has_already_run(): void
    {
        $michael = Employee::factory()->create([
            'locked' => true,
        ]);
        $scheduledAction = ScheduledAction::factory()->create([
            'employee_id' => $michael->id,
            'content' => json_encode(['task_name' => 'Create a new project']),
            'processed' => true,
        ]);

        (new ProcessActionCreateTask)->execute($scheduledAction);

        $this->assertDatabaseMissing('tasks', [
            'employee_id' => $michael->id,
            'title' => 'Create a new project',
        ]);
    }
}
