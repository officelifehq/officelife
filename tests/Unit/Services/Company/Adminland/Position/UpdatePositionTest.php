<?php

namespace Tests\Unit\Services\Company\Adminland\Position;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Jobs\Logs\LogAccountAudit;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Position\UpdatePosition;

class UpdatePositionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_position() : void
    {
        Queue::fake();

        $position = factory(Position::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $position->company_id,
        ]);

        $request = [
            'company_id' => $position->company_id,
            'author_id' => $michael->user->id,
            'position_id' => $position->id,
            'title' => 'Assistant Regional Manager',
        ];

        $newPosition = (new UpdatePosition)->execute($request);

        $this->assertDatabaseHas('positions', [
            'id' => $position->id,
            'company_id' => $position->company_id,
            'title' => 'Assistant Regional Manager',
        ]);

        $this->assertInstanceOf(
            Position::class,
            $position
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $position, $newPosition) {
            return $job->auditLog['action'] === 'position_updated' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'position_id' => $newPosition->id,
                    'position_title' => $newPosition->title,
                    'position_old_title' => $position->title,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'title' => 'Assistant Regional Manager',
        ];

        $this->expectException(ValidationException::class);
        (new UpdatePosition)->execute($request);
    }
}
