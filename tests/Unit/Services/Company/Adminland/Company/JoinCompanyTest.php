<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\User\User;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\UserAlreadyInvitedException;
use App\Services\Company\Adminland\Company\JoinCompany;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JoinCompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_joins_a_company(): void
    {
        Queue::fake();

        $dwight = User::factory()->create([]);
        $company = Company::factory()->create();

        $request = [
            'user_id' => $dwight->id,
            'code' => 'USD',
        ];

        $company = (new JoinCompany)->execute($request);

        $this->assertInstanceOf(
            Company::class,
            $company
        );

        $this->assertDatabaseHas('employees', [
            'user_id' => $dwight->id,
            'email' => $dwight->email,
        ]);

        $employee = Employee::where('user_id', $dwight->id)->first();

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($employee, $company) {
            return $job->auditLog['action'] === 'employee_joined_company' &&
                $job->auditLog['author_id'] === $employee->id &&
                $job->auditLog['objects'] === json_encode([
                    'company_name' => $company->name,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_the_code_is_not_valid(): void
    {
        Queue::fake();

        $dwight = User::factory()->create([]);
        $company = Company::factory()->create();

        $request = [
            'user_id' => $dwight->id,
            'code' => 'abc123',
        ];

        $this->expectException(ValidationException::class);
        (new JoinCompany)->execute($request);
    }

    /** @test */
    public function it_fails_if_user_already_invited(): void
    {
        Queue::fake();

        $dwight = User::factory()->create([]);
        $company = Company::factory()->create();

        $employee = Employee::factory()->create([
            'user_id' => $dwight->id,
        ]);

        $request = [
            'user_id' => $dwight->id,
            'code' => $company->code_to_join_company,
        ];

        $this->expectException(UserAlreadyInvitedException::class);
        (new JoinCompany)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $dwight = User::factory()->create([]);

        $request = [
            'author_id' => $dwight->id,
        ];

        $this->expectException(ValidationException::class);
        (new JoinCompany)->execute($request);
    }
}
