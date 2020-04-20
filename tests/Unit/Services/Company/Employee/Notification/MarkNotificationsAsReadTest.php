<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\Notification;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\User\Notification\MarkNotificationsAsRead;

class MarkNotificationsAsReadTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_marks_all_notifications_in_the_account_as_read_regardless_of_permission_level(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael, $michael);

        $michael = $this->createHR();
        $this->executeService($michael, $michael);

        $michael = $this->createEmployee();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function it_fails_if_the_employee_is_not_the_author(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createEmployee();

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight);
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

    /** @test */
    public function it_fails_if_the_employee_is_not_in_the_company(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createEmployee();
        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new MarkNotificationsAsRead)->execute($request);
    }

    private function executeService(Employee $michael, Employee $dwight): void
    {
        factory(Notification::class, 2)->create([
            'employee_id' => $dwight->id,
            'read' => false,
        ]);

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
        ];

        $result = (new MarkNotificationsAsRead)->execute($request);

        $this->assertDatabaseHas('notifications', [
            'employee_id' => $dwight->id,
            'read' => true,
        ]);

        $this->assertTrue(
            $result
        );
    }
}
