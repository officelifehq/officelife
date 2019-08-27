<?php

namespace Tests\Unit\Services\Company\Employee\Position;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Position\AssignPositionToEmployee;

class AssignPositionToEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_assigns_a_position() : void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        $position = factory(Position::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'position_id' => $position->id,
        ];

        $michael = (new AssignPositionToEmployee)->execute($request);

        $this->assertDatabaseHas('employees', [
            'company_id' => $michael->company_id,
            'id' => $michael->id,
            'position_id' => $position->id,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $position) {
            return $job->auditLog['action'] === 'position_assigned' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'position_id' => $position->id,
                    'position_title' => $position->title,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $position) {
            return $job->auditLog['action'] === 'position_assigned' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'position_id' => $position->id,
                    'position_title' => $position->title,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new AssignPositionToEmployee)->execute($request);
    }
}
