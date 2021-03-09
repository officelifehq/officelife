<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\User\User;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\AddUserToCompany;

class AddUserToCompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_user_to_a_company_as_administrator(): void
    {
        Queue::fake();

        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_adds_a_user_to_a_company_as_hr(): void
    {
        Queue::fake();

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
        User::factory()->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ];

        $this->expectException(ValidationException::class);
        (new AddUserToCompany)->execute($request);
    }

    protected function executeService(Employee $michael): void
    {
        $user = User::factory()->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'user_id' => $user->id,
            'permission_level' => config('officelife.permission_level.user'),
        ];

        $dwight = (new AddUserToCompany)->execute($request);

        $this->assertInstanceOf(
            Employee::class,
            $dwight
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'user_added_to_company' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'user_id' => $dwight->user->id,
                    'user_email' => $dwight->user->email,
                ]);
        });

        $this->assertDatabaseHas('employees', [
            'id' => $dwight->id,
            'user_id' => $user->id,
            'company_id' => $michael->company_id,
        ]);
    }
}
