<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\EmployeeLog;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeLogTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $employeeLog = EmployeeLog::factory()->create([]);
        $this->assertTrue($employeeLog->employee()->exists());
    }

    /** @test */
    public function it_belongs_to_an_author(): void
    {
        $employeeLog = EmployeeLog::factory()->create([]);
        $this->assertTrue($employeeLog->author()->exists());
    }

    /** @test */
    public function it_returns_the_date_attribute(): void
    {
        $employeeLog = EmployeeLog::factory()->create([
            'audited_at' => '2017-01-22 17:56:03',
        ]);
        $this->assertEquals(
            'Jan 22, 2017 05:56 PM',
            $employeeLog->date
        );
    }

    /** @test */
    public function it_returns_the_object_attribute(): void
    {
        $employeeLog = EmployeeLog::factory()->create([]);
        $this->assertEquals(
            1,
            $employeeLog->object->{'user'}
        );
    }

    /** @test */
    public function it_returns_the_content_attribute(): void
    {
        $michael = $this->createAdministrator();

        $auditLog = EmployeeLog::factory()->create([
            'action' => 'employee_worklog_logged',
            'objects' => json_encode([
                'author_id' => $michael->user->id,
                'employee_name' => $michael->user->name,
            ]),
            'employee_id' => $michael->id,
        ]);

        $this->assertEquals(
            'Added a worklog.',
            $auditLog->content
        );
    }
}
