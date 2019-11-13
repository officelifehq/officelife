<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\Employee;
use App\Models\Company\Notification;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\User\Notification\MarkNotificationsAsRead;

class MarkNotificationsAsReadTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_marks_all_notifications_in_the_account_as_read(): void
    {
        $user = factory(User::class)->create([]);
        $employee = factory(Employee::class)->create([
            'user_id' => $user->id,
        ]);

        factory(Notification::class, 2)->create([
            'employee_id' => $employee->id,
        ]);

        $request = [
            'employee_id' => $employee->id,
        ];

        $result = (new MarkNotificationsAsRead)->execute($request);

        $this->assertDatabaseHas('notifications', [
            'employee_id' => $employee->id,
            'read' => 0,
        ]);

        $this->assertTrue(
            $result
        );
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'action' => 'account_created',
        ];

        $this->expectException(ValidationException::class);
        (new MarkNotificationsAsRead)->execute($request);
    }
}
