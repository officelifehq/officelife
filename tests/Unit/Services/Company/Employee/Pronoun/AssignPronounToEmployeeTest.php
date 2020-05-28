<?php

namespace Tests\Unit\Services\Company\Employee\Pronoun;

use Tests\TestCase;
use App\Models\User\Pronoun;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Pronoun\AssignPronounToEmployee;

class AssignPronounToEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_assigns_a_pronoun_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_assigns_a_pronoun_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_assigns_a_pronoun_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service_against_another_user(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight);
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

    /** @test */
    public function it_fails_if_the_employee_is_not_in_the_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_the_position_does_not_exist_in_the_instance(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'pronoun_id' => '13233',
        ];

        $this->expectException(ValidationException::class);
        (new AssignPronounToEmployee)->execute($request);
    }

    private function executeService(Employee $michael, Employee $dwight): void
    {
        Queue::fake();

        $pronoun = factory(Pronoun::class)->create();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'pronoun_id' => $pronoun->id,
        ];

        $dwight = (new AssignPronounToEmployee)->execute($request);

        $this->assertDatabaseHas('employees', [
            'id' => $dwight->id,
            'company_id' => $dwight->company_id,
            'pronoun_id' => $pronoun->id,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $dwight
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $pronoun, $dwight) {
            return $job->auditLog['action'] === 'pronoun_assigned_to_employee' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
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
}
