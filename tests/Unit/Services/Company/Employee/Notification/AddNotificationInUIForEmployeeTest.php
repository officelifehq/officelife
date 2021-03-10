<?php

namespace Tests\Unit\Services\Company\Employee\Notification;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Notification\AddNotificationInUIForEmployee;

class AddNotificationInUIForEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_a_notification(): void
    {
        $employee = Employee::factory()->create();

        $request = [
            'employee_id' => $employee->id,
            'action' => 'task_assigned',
            'objects' => json_encode(['team_id' => 1]),
        ];

        $notification = (new AddNotificationInUIForEmployee)->execute($request);

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'employee_id' => $employee->id,
            'action' => 'task_assigned',
            'objects' => json_encode(['team_id' => 1]),
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'action' => 'account_created',
        ];

        $this->expectException(ValidationException::class);
        (new AddNotificationInUIForEmployee)->execute($request);
    }
}
