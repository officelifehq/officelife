<?php

namespace Tests\Unit\Services\Company\Position;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use Illuminate\Validation\ValidationException;
use App\Services\Adminland\Position\CreatePosition;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreatePositionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_position()
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'title' => 'Assistant to the regional manager',
        ];

        $position = (new CreatePosition)->execute($request);

        $this->assertDatabaseHas('positions', [
            'id' => $position->id,
            'company_id' => $employee->company_id,
            'title' => 'Assistant to the regional manager',
        ]);

        $this->assertInstanceOf(
            Position::class,
            $position
        );
    }

    /** @test */
    public function it_logs_an_action()
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'title' => 'Assistant to the regional manager',
        ];

        (new CreatePosition)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'position_created',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new CreatePosition)->execute($request);
    }
}
