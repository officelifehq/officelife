<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ScheduledAction;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScheduledActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_action(): void
    {
        $scheduledAction = ScheduledAction::factory()->create([]);
        $this->assertTrue($scheduledAction->action()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $scheduledAction = ScheduledAction::factory()->create([]);
        $this->assertTrue($scheduledAction->employee()->exists());
    }
}
