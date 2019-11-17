<?php

namespace Tests\Unit\Services\Company\Employee\Position;

use Tests\TestCase;
use App\Models\User\Pronoun;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Pronoun\AssignPronounToEmployee;

class AssignPronounToEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_assigns_a_pronoun(): void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        $pronoun = factory(Pronoun::class)->create();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'pronoun_id' => $pronoun->id,
        ];

        $michael = (new AssignPronounToEmployee)->execute($request);

        $this->assertDatabaseHas('employees', [
            'company_id' => $michael->company_id,
            'id' => $michael->id,
            'pronoun_id' => $pronoun->id,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $pronoun) {
            return $job->auditLog['action'] === 'pronoun_assigned_to_employee' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'pronoun_id' => $pronoun->id,
                    'pronoun_label' => $pronoun->label,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $pronoun) {
            return $job->auditLog['action'] === 'pronoun_assigned' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'pronoun_id' => $pronoun->id,
                    'pronoun_label' => $pronoun->label,
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
        (new AssignPronounToEmployee)->execute($request);
    }
}
