<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\Employee;
use App\Jobs\Logs\LogAccountAudit;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\AddUserToCompany;

class AddUserToCompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_user_to_a_company() : void
    {
        Queue::fake();

        $michael = $this->createAdministrator();
        $user = factory(User::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user->id,
            'user_id' => $user->id,
            'permission_level' => config('homas.authorizations.user'),
        ];

        $dwight = (new AddUserToCompany)->execute($request);

        $this->assertInstanceOf(
            Employee::class,
            $dwight
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'user_added_to_company' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
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

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $michael = $this->createAdministrator();
        factory(User::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user->id,
        ];

        $this->expectException(ValidationException::class);
        (new AddUserToCompany)->execute($request);
    }
}
