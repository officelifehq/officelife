<?php

namespace Tests\Unit\Services\Company\Adminland\Position;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Position\DestroyPosition;

class DestroyPositionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_position()
    {
        $position = factory(Position::class)->create([]);
        $employee = factory(Employee::class)->create([
            'company_id' => $position->company_id,
        ]);

        $request = [
            'company_id' => $position->company_id,
            'author_id' => $employee->user->id,
            'position_id' => $position->id,
        ];

        (new DestroyPosition)->execute($request);

        $this->assertDatabaseMissing('positions', [
            'id' => $position->id,
        ]);
    }

    /** @test */
    public function it_logs_an_action()
    {
        $position = factory(Position::class)->create([]);
        $employee = factory(Employee::class)->create([
            'company_id' => $position->company_id,
        ]);

        $request = [
            'company_id' => $position->company_id,
            'author_id' => $employee->user->id,
            'position_id' => $position->id,
        ];

        (new DestroyPosition)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $position->company_id,
            'action' => 'position_destroyed',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyPosition)->execute($request);
    }
}
