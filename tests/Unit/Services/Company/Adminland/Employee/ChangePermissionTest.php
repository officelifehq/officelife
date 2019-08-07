<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Jobs\Logs\LogAccountAudit;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Employee\ChangePermission;

class ChangePermissionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_changes_permission() : void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user_id,
            'employee_id' => $michael->id,
            'permission_level' => config('homas.authorizations.hr'),
        ];

        $michael = (new ChangePermission)->execute($request);

        $this->assertDatabaseHas('employees', [
            'id' => $michael->id,
            'permission_level' => config('homas.authorizations.hr'),
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'permission_changed' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'old_permission' => config('homas.authorizations.administrator'),
                    'new_permission' => config('homas.authorizations.hr'),
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user_id,
        ];

        $this->expectException(ValidationException::class);
        (new ChangePermission)->execute($request);
    }
}
