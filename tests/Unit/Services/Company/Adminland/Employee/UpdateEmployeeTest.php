<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Employee\UpdateEmployee;

class UpdateEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_an_employee(): void
    {
        Queue::fake();

        $michael = Employee::factory()->asHR()->create();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'email' => 'dwight@dundermifflin.com',
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
        ];

        $dwight = (new UpdateEmployee)->execute($request);

        $this->assertDatabaseHas('employees', [
            'id' => $michael->id,
            'company_id' => $michael->company_id,
            'email' => 'dwight@dundermifflin.com',
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $dwight
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'employee_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => 'Dwight Schrute',
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
        (new UpdateEmployee)->execute($request);
    }
}
