<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Employee;
use App\Models\Company\EmployeeLog;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeLogTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $employeeLog = factory(EmployeeLog::class)->create([]);
        $this->assertTrue($employeeLog->employee()->exists());
    }

    /** @test */
    public function it_returns_an_object(): void
    {
        $michael = factory(Employee::class)->create([
            'first_name' => 'michael',
            'last_name' => 'scott',
        ]);
        $log = factory(EmployeeLog::class)->create([
            'author_id' => $michael->id,
            'author_name' => 'michael scott',
            'action' => 'account_created',
            'created_at' => '2020-01-12 00:00:00',
        ]);

        $this->assertEquals(
            [
                'id' => $log->id,
                'action' => 'account_created',
                'objects' => json_decode('{"user": 1}'),
                'localized_content' => '',
                'author' => [
                    'id' => $michael->id,
                    'name' => 'michael scott',
                ],
                'localized_created_at' => 'Jan 12, 2020 00:00',
                'created_at' => '2020-01-12 00:00:00',
            ],
            $log->toObject()
        );
    }

    /** @test */
    public function it_returns_the_date_attribute(): void
    {
        $employeeLog = factory(EmployeeLog::class)->create([
            'audited_at' => '2017-01-22 17:56:03',
        ]);
        $this->assertEquals(
            'Jan 22, 2017 17:56',
            $employeeLog->date
        );
    }

    /** @test */
    public function it_returns_the_object_attribute(): void
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
        $michael = $this->createAdministrator();

        $auditLog = factory(EmployeeLog::class)->create([
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
