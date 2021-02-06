<?php

namespace Tests\Unit\Services\Company\Employee\EmployeeStatus;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\ECoffee;
use App\Models\Company\Employee;
use App\Models\Company\ECoffeeMatch;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\ECoffee\MarkECoffeeSessionAsHappened;

class MarkECoffeeSessionAsHappenedTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_marks_an_ecoffee_session_as_happened_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $eCoffee = ECoffee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $eCoffeeMatch = ECoffeeMatch::factory()->create([
            'e_coffee_id' => $eCoffee->id,
            'employee_id' => $michael->id,
        ]);

        $this->executeService($michael, $eCoffee, $eCoffeeMatch);
    }

    /** @test */
    public function it_marks_an_ecoffee_session_as_happened_as_hr(): void
    {
        $michael = $this->createHR();
        $eCoffee = ECoffee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $eCoffeeMatch = ECoffeeMatch::factory()->create([
            'e_coffee_id' => $eCoffee->id,
            'employee_id' => $michael->id,
        ]);

        $this->executeService($michael, $eCoffee, $eCoffeeMatch);
    }

    /** @test */
    public function it_marks_an_ecoffee_session_as_happened_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $eCoffee = ECoffee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $eCoffeeMatch = ECoffeeMatch::factory()->create([
            'e_coffee_id' => $eCoffee->id,
            'employee_id' => $michael->id,
        ]);

        $this->executeService($michael, $eCoffee, $eCoffeeMatch);
    }

    /** @test */
    public function no_one_except_employee_can_mark_an_ecoffee_session_as_happened_even_admin(): void
    {
        $michael = $this->createAdministrator();
        $eCoffee = ECoffee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $eCoffeeMatch = ECoffeeMatch::factory()->create([
            'e_coffee_id' => $eCoffee->id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $eCoffee, $eCoffeeMatch);
    }

    /** @test */
    public function it_fails_if_ecoffee_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $eCoffee = ECoffee::factory()->create([]);
        $eCoffeeMatch = ECoffeeMatch::factory()->create([
            'e_coffee_id' => $eCoffee->id,
            'employee_id' => $michael->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $eCoffee, $eCoffeeMatch);
    }

    /** @test */
    public function it_fails_if_ecoffee_match_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $eCoffee = ECoffee::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $eCoffeeMatch = ECoffeeMatch::factory()->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $eCoffee, $eCoffeeMatch);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new MarkECoffeeSessionAsHappened)->execute($request);
    }

    private function executeService(Employee $michael, ECoffee $eCoffee, ECoffeeMatch $match): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'e_coffee_id' => $eCoffee->id,
            'e_coffee_match_id' => $match->id,
        ];

        (new MarkECoffeeSessionAsHappened)->execute($request);

        $this->assertDatabaseHas('e_coffee_matches', [
            'id' => $match->id,
            'happened' => true,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $match) {
            return $job->auditLog['action'] === 'e_coffee_match_session_as_happened' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'other_employee_id' => $match->employeeMatchedWith->id,
                    'other_employee_name' => $match->employeeMatchedWith->name,
                ]);
        });
    }
}
