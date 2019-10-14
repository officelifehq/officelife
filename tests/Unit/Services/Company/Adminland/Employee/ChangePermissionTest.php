<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
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
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'permission_level' => config('villagers.authorizations.hr'),
        ];

        $michael = (new ChangePermission)->execute($request);

        $this->assertDatabaseHas('employees', [
            'id' => $michael->id,
            'permission_level' => config('villagers.authorizations.hr'),
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'permission_changed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'old_permission' => config('villagers.authorizations.administrator'),
                    'new_permission' => config('villagers.authorizations.hr'),
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ];

        $this->expectException(ValidationException::class);
        (new ChangePermission)->execute($request);
    }
}
