<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\BaseServiceAction;
use App\Models\Company\ScheduledAction;
use App\Exceptions\MissingInformationInJsonAction;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BaseServiceActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_an_empty_keys_array(): void
    {
        $stub = $this->getMockForAbstractClass(BaseServiceAction::class);

        $this->assertIsArray(
            $stub->keys()
        );
    }

    /** @test */
    public function it_raises_an_exception_if_a_key_is_missing_in_json(): void
    {
        $keys = [
            'product_name' => 'test',
        ];

        $scheduledAction = ScheduledAction::factory()->create([
            'content' => json_encode(['name' => 'test']),
        ]);

        $stub = $this->getMockForAbstractClass(BaseServiceAction::class, [], '', true, true, true, ['keys']);

        $stub->expects($this->any())
             ->method('keys')
             ->will($this->returnValue($keys));

        $this->expectException(MissingInformationInJsonAction::class);
        $stub->validateJsonStructure($scheduledAction);
    }

    /** @test */
    public function it_marks_a_scheduled_action_as_processed(): void
    {
        $scheduledAction = ScheduledAction::factory()->create();

        $stub = $this->getMockForAbstractClass(BaseServiceAction::class);
        $stub->markAsProcessed($scheduledAction);

        $this->assertDatabaseHas('scheduled_actions', [
            'id' => $scheduledAction->id,
            'processed' => true,
        ]);
    }
}
