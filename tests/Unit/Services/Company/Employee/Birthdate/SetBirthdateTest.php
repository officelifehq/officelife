<?php

namespace Tests\Unit\Services\Company\Employee\Birthdate;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Birthdate\SetBirthdate;

class SetBirthdateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_sets_the_birthdate_of_the_employee(): void
    {
        Queue::fake();

        Carbon::setTestNow(Carbon::create(2017, 1, 1));
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'date' => '1978-10-01',
        ];

        $michael = (new SetBirthdate)->execute($request);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        $this->assertDatabaseHas('employees', [
            'id' => $michael->id,
            'birthdate' => '1978-10-01 00:00:00',
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
                    'birthday' => '1978-10-01',
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
        (new SetBirthdate)->execute($request);
    }
}
