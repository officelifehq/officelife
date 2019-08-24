<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\EmployeeLog;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeLogTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_employee() : void
    {
        $employeeLog = factory(EmployeeLog::class)->create([]);
        $this->assertTrue($employeeLog->employee()->exists());
    }

    /** @test */
    public function it_returns_the_date_attribute() : void
    {
        $employeeLog = factory(EmployeeLog::class)->create([
            'created_at' => '2017-01-22 17:56:03',
        ]);
        $this->assertEquals(
            'Jan 22, 2017 17:56',
            $employeeLog->date
        );
    }

    /** @test */
    public function it_returns_the_object_attribute() : void
    {
        $employeeLog = factory(EmployeeLog::class)->create([]);
        $this->assertEquals(
            1,
            $employeeLog->object->{'user'}
        );
    }

    /** @test */
    public function it_returns_the_content_attribute(): void
    {
        $adminEmployee = $this->createAdministrator();

        $auditLog = factory(EmployeeLog::class)->create([
            'action' => 'employee_worklog_logged',
            'objects' => json_encode([
                'author_id' => $adminEmployee->user->id,
                'employee_name' => $adminEmployee->user->name,
            ]),
            'employee_id' => $adminEmployee->id,
        ]);

        $this->assertEquals(
            'Added a worklog.',
            $auditLog->content
        );
    }
}
