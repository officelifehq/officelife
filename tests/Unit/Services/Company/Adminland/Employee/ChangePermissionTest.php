<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\Employee\ChangePermission;

class ChangePermissionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_changes_permission_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael, config('officelife.permission_level.administrator'));
    }

    /** @test */
    public function it_changes_permission_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael, config('officelife.permission_level.hr'));
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, config('officelife.permission_level.user'));
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ];

        $this->expectException(ValidationException::class);
        (new ChangePermission)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_employee_does_not_match_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = Employee::factory()->create();

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'permission_level' => config('officelife.permission_level.hr'),
        ];

        $this->expectException(ModelNotFoundException::class);
        (new ChangePermission)->execute($request);
    }

    private function executeService(Employee $michael, int $permission): void
    {
        Queue::fake();
        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'permission_level' => config('officelife.permission_level.hr'),
        ];

        $michael = (new ChangePermission)->execute($request);

        $this->assertDatabaseHas('employees', [
            'id' => $michael->id,
            'permission_level' => config('officelife.permission_level.hr'),
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $permission) {
            return $job->auditLog['action'] === 'permission_changed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'old_permission' => $permission,
                    'new_permission' => config('officelife.permission_level.hr'),
                ]);
        });
    }
}
