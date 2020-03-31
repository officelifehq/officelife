<?php

namespace Tests\Unit\Services\Company\Employee\WorkFromHome;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\WorkFromHome\UpdateWorkFromHomeInformation;

class UpdateWorkFromHomeInformationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_new_work_from_home_entry_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_creates_a_new_work_from_home_entry_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function user_can_execute_the_service_in_his_own_profile(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_deletes_an_existing_entry(): void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);

        // we need to call the service twice.
        // once to create entry, the other one to trigger its deletion
        $michael = (new UpdateWorkFromHomeInformation)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'date' => '2018-01-01',
            'work_from_home' => true,
        ]);
        $michael = (new UpdateWorkFromHomeInformation)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'date' => '2018-01-01',
            'work_from_home' => false,
        ]);

        $this->assertEquals(
            0,
            DB::table('employee_work_from_home')->where('employee_id', $michael->id)->count()
        );

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'employee_work_from_home_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'date' => '2018-01-01',
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'work_from_home_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'date' => '2018-01-01',
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
        ];

        $this->expectException(ValidationException::class);
        (new UpdateWorkFromHomeInformation)->execute($request);
    }

    private function executeService(Employee $michael, Employee $dwight): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'date' => '2018-01-01',
            'work_from_home' => true,
        ];

        $dwight = (new UpdateWorkFromHomeInformation)->execute($request);

        $this->assertInstanceOf(
            Employee::class,
            $dwight
        );

        $this->assertDatabaseHas('employee_work_from_home', [
            'employee_id' => $dwight->id,
            'date' => '2018-01-01 00:00:00',
            'work_from_home' => true,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'employee_work_from_home_logged' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'date' => '2018-01-01',
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'work_from_home_logged' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'date' => '2018-01-01',
                ]);
        });

        // now we call the method again --- we should only have one entry in the
        // database as the service doesn't create an additional entry
        $dwight = (new UpdateWorkFromHomeInformation)->execute($request);
        $this->assertEquals(
            1,
            DB::table('employee_work_from_home')->where('employee_id', $dwight->id)->count()
        );
    }
}
