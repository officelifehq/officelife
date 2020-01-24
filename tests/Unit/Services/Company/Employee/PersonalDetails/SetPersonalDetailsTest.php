<?php

namespace Tests\Unit\Services\Company\Employee\PersonalDetails;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\PersonalDetails\SetPersonalDetails;

class SetPersonalDetailsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_sets_the_name_and_email_of_the_employee(): void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([
            'first_name' => null,
            'last_name' => null,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'first_name' => 'michael',
            'last_name' => 'scott',
            'email' => 'michael@dundermifflin.com',
        ];

        $michael = (new SetPersonalDetails)->execute($request);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        $this->assertDatabaseHas('employees', [
            'id' => $michael->id,
            'first_name' => 'michael',
            'last_name' => 'scott',
            'email' => 'michael@dundermifflin.com',
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'employee_personal_details_set' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'employee_email' => 'michael@dundermifflin.com',
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'personal_details_set' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'name' => $michael->name,
                    'email' => 'michael@dundermifflin.com',
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
        (new SetPersonalDetails)->execute($request);
    }
}
