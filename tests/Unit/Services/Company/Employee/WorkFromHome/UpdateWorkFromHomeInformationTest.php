<?php

namespace Tests\Unit\Services\Company\Employee\PersonalDetails;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Morale\UpdateWorkFromHomeInformation;

class UpdateWorkFromHomeInformationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_new_work_from_home_entry(): void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'date' => '2018-01-01',
            'work_from_home' => true,
        ];

        $michael = (new UpdateWorkFromHomeInformation)->execute($request);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        $this->assertDatabaseHas('employee_work_from_home', [
            'employee_id' => $michael->id,
            'date' => '2018-01-01 00:00:00',
            'work_from_home' => true,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'employee_work_from_home_logged' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
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
        $michael = (new UpdateWorkFromHomeInformation)->execute($request);
        $this->assertEquals(
            1,
            DB::table('employee_work_from_home')->count()
        );
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
}
