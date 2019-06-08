<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\User\Notification;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\User\Notification\CreateNotificationInUIForEmployee;

class CreateNotificationInUIForEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_a_notification_without_a_company_associated_with_it()
    {
        $user = factory(User::class)->create([]);

        $request = [
            'user_id' => $user->id,
            'action' => 'task_assigned',
            'content' => '{team_id: 1}',
        ];

        $notification = (new CreateNotificationInUIForEmployee)->execute($request);

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'user_id' => $user->id,
            'company_id' => null,
            'action' => 'task_assigned',
            'content' => '{team_id: 1}',
        ]);

        $this->assertInstanceOf(
            Notification::class,
            $notification
        );
    }

    /** @test */
    public function it_logs_a_notification()
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'user_id' => $employee->user_id,
            'company_id' => $employee->company_id,
            'action' => 'task_assigned',
            'content' => '{team_id: 1}',
        ];

        $notification = (new CreateNotificationInUIForEmployee)->execute($request);

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'user_id' => $employee->user_id,
            'company_id' => $employee->company_id,
            'action' => 'task_assigned',
            'content' => '{team_id: 1}',
        ]);
    }

    /** @test */
    public function it_fails_to_log_the_notification_when_the_user_doesnt_belong_to_the_company()
    {
        $employee = factory(Employee::class)->create([]);
        $company = factory(Company::class)->create([]);

        $request = [
            'user_id' => $employee->user_id,
            'company_id' => $company->id,
            'action' => 'task_assigned',
            'content' => '{team_id: 1}',
        ];

        $this->expectException(ModelNotFoundException::class);
        (new CreateNotificationInUIForEmployee)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'action' => 'account_created',
        ];

        $this->expectException(ValidationException::class);
        (new CreateNotificationInUIForEmployee)->execute($request);
    }
}
