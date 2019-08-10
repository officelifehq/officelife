<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Employee\UpdateHiringInformation;

class UpdateHiringInformationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_the_hiring_information() : void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user->id,
            'employee_id' => $michael->id,
            'hired_at' => '2010-02-20',
        ];

        $updatedEmployee = (new UpdateHiringInformation)->execute($request);

        $this->assertDatabaseHas('employees', [
            'id' => $michael->id,
            'company_id' => $michael->company_id,
            'hired_at' => '2010-02-20 00:00:00',
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $updatedEmployee
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'employee_updated_hiring_information' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'hired_at' => '2010-02-20',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateHiringInformation)->execute($request);
    }
}
