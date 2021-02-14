<?php

namespace Tests\Unit\Services\Company\Adminland\ECoffee;

use Tests\TestCase;
use App\Models\User\User;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\ECoffee\ToggleECoffeeProcess;

class ToggleECoffeeProcessTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_toggles_the_ecoffee_process_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_toggles_the_ecoffee_process_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function normal_user_cant_call_the_service(): void
    {
        $michael = $this->createEmployee();

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = $this->createAdministrator();
        factory(User::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new ToggleECoffeeProcess)->execute($request);
    }

    protected function executeService(Employee $michael): void
    {
        Queue::fake();

        $user = factory(User::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ];

        $company = (new ToggleECoffeeProcess)->execute($request);

        $this->assertInstanceOf(
            Company::class,
            $company
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'toggle_e_coffee_process' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([]);
        });

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'e_coffee_enabled' => $company->e_coffee_enabled,
        ]);
    }
}
