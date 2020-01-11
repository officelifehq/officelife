<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\Employee;
use App\Models\Company\Notification;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\User\Notification\MarkNotificationsAsRead;

class MarkNotificationsAsReadTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_marks_all_notifications_in_the_account_as_read(): void
    {
        $user = factory(User::class)->create([]);
        $michael = factory(Employee::class)->create([
            'user_id' => $user->id,
        ]);

        factory(Notification::class, 2)->create([
            'employee_id' => $michael->id,
            'read' => false,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
        ];

        $result = (new MarkNotificationsAsRead)->execute($request);

        $this->assertDatabaseHas('notifications', [
            'employee_id' => $michael->id,
            'read' => true,
        ]);

        $this->assertTrue(
            $result
        );
    }

    /** @test */
    public function it_fails_if_the_employee_is_not_an_administrator(): void
    {
        $michael = factory(Employee::class)->create([]);
        $dwight = factory(Employee::class)->create([]);

        factory(Notification::class, 2)->create([
            'employee_id' => $michael->id,
        ]);

        $request = [
            'author_id' => $dwight->id,
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new MarkNotificationsAsRead)->execute($request);
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
