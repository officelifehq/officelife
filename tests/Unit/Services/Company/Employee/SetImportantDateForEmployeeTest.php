<?php

namespace Tests\Unit\Services\Company\Employee;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\SetImportantDateForEmployee;

class SetImportantDateForEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Set the base for the test.
     *
     * @return Employee
     */
    private function initialize() : Employee
    {
        Queue::fake();

        Carbon::setTestNow(Carbon::create(2017, 1, 1));
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'occasion' => 'birthdate',
            'date' => '1978-10-01',
        ];

        return (new SetImportantDateForEmployee)->execute($request);
    }

    /** @test */
    public function it_sets_the_important_date_of_an_employee() : void
    {
        $michael = $this->initialize();

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        $this->assertDatabaseHas('employee_important_dates', [
            'employee_id' => $michael->id,
            'occasion' => 'birthdate',
            'date' => '1978-10-01 00:00:00',
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'employee_birthday_set' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'birthday' => '1978-10-01',
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'birthday_set' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'birthday' => '1978-10-01',
                ]);
        });
    }

    /** @test */
    public function it_creates_an_important_date() : void
    {
        $this->assertDatabaseMissing('employee_important_dates', [
            'occasion' => 'birthdate',
            'date' => '1978-10-01 00:00:00',
        ]);

        $michael = $this->initialize();

        $this->assertDatabaseHas('employee_important_dates', [
            'employee_id' => $michael->id,
            'occasion' => 'birthdate',
            'date' => '1978-10-01 00:00:00',
        ]);
    }

    /** @test */
    public function it_creates_an_event() : void
    {
        $this->assertDatabaseMissing('employee_events', [
            'label' => 'birthdate',
            'date' => '2017-10-01 00:00:00',
        ]);

        $michael = $this->initialize();

        $this->assertDatabaseHas('employee_events', [
            'employee_id' => $michael->id,
            'company_id' => $michael->company_id,
            'label' => 'birthdate',
            'date' => '2017-10-01 00:00:00',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
        ];

        $this->expectException(ValidationException::class);
        (new SetImportantDateForEmployee)->execute($request);
    }
}
