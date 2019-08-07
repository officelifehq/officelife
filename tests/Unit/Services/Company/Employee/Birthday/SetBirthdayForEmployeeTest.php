<?php

namespace Tests\Unit\Services\Company\Employee\Birthday;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Employee;
use App\Jobs\Logs\LogAccountAudit;
use App\Jobs\Logs\LogEmployeeAudit;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Birthday\SetBirthdayForEmployee;

class SetBirthdayForEmployeeTest extends TestCase
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
            'author_id' => $michael->user_id,
            'employee_id' => $michael->id,
            'date' => '1978-10-01',
        ];

        return (new SetBirthdayForEmployee)->execute($request);
    }

    /** @test */
    public function it_sets_the_birthday_of_an_employee() : void
    {
        $michael = $this->initialize();

        $this->assertDatabaseHas('employees', [
            'id' => $michael->id,
            'company_id' => $michael->company_id,
            'birthdate' => '1978-10-01 00:00:00',
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'employee_birthday_set' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'birthday' => '1978-10-01',
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'birthday_set' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'birthday' => '1978-10-01',
                ]);
        });
    }

    /** @test */
    public function it_creates_an_event_for_the_birthdate() : void
    {
        $michael = $this->initialize();

        $this->assertDatabaseHas('employee_events', [
            'employee_id' => $michael->id,
            'company_id' => $michael->company_id,
            'label' => 'birthday',
            'date' => '2017-10-01 00:00:00',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user_id,
            'employee_id' => $michael->id,
        ];

        $this->expectException(ValidationException::class);
        (new SetBirthdayForEmployee)->execute($request);
    }
}
