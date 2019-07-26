<?php

namespace Tests\Unit\Services\Company\Adminland\Position;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Position\UpdatePosition;

class UpdatePositionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_position() : void
    {
        $position = factory(Position::class)->create([]);
        $employee = factory(Employee::class)->create([
            'company_id' => $position->company_id,
        ]);

        $request = [
            'company_id' => $position->company_id,
            'author_id' => $employee->user->id,
            'position_id' => $position->id,
            'title' => 'Assistant Regional Manager',
        ];

        $position = (new UpdatePosition)->execute($request);

        $this->assertDatabaseHas('positions', [
            'id' => $position->id,
            'company_id' => $position->company_id,
            'title' => 'Assistant Regional Manager',
        ]);

        $this->assertInstanceOf(
            Position::class,
            $position
        );
    }

    /** @test */
    public function it_logs_an_action() : void
    {
        $position = factory(Position::class)->create([]);
        $employee = factory(Employee::class)->create([
            'company_id' => $position->company_id,
        ]);

        $request = [
            'company_id' => $position->company_id,
            'author_id' => $employee->user->id,
            'position_id' => $position->id,
            'title' => 'Assistant Regional Manager',
        ];

        (new UpdatePosition)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $position->company_id,
            'action' => 'position_updated',
        ]);
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
