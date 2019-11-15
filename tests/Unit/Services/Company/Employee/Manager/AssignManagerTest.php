<?php

namespace Tests\Unit\Services\Company\Employee\Manager;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use App\Exceptions\SameIdsException;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Manager\AssignManager;

class AssignManagerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_assigns_a_manager(): void
    {
        Queue::fake();

        $dwight = factory(Employee::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $dwight->company_id,
        ]);

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $dwight->id,
            'employee_id' => $dwight->id,
            'manager_id' => $michael->id,
        ];

        $michael = (new AssignManager)->execute($request);

        $this->assertDatabaseHas('direct_reports', [
            'company_id' => $dwight->company_id,
            'employee_id' => $dwight->id,
            'manager_id' => $michael->id,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $dwight
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'manager_assigned' &&
                $job->auditLog['author_id'] === $dwight->id &&
                $job->auditLog['objects'] === json_encode([
                    'manager_id' => $michael->id,
                    'manager_name' => $michael->name,
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'manager_assigned' &&
                $job->auditLog['author_id'] === $dwight->id &&
                $job->auditLog['objects'] === json_encode([
                    'manager_id' => $michael->id,
                    'manager_name' => $michael->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'direct_report_assigned' &&
                $job->auditLog['author_id'] === $dwight->id &&
                $job->auditLog['objects'] === json_encode([
                    'direct_report_id' => $dwight->id,
                    'direct_report_name' => $dwight->name,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new AssignManager)->execute($request);
    }

    /** @test */
    public function it_fails_if_employee_and_manager_are_the_same_person(): void
    {
        $dwight = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $dwight->id,
            'employee_id' => $dwight->id,
            'manager_id' => $dwight->id,
        ];

        $this->expectException(SameIdsException::class);
        (new AssignManager)->execute($request);
    }
}
