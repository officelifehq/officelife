<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\AddUserToCompany;

class AddUserToCompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_user_to_a_company() : void
    {
        $adminEmployee = $this->createAdministrator();
        $user = factory(User::class)->create([]);

        $request = [
            'company_id' => $adminEmployee->company_id,
            'author_id' => $adminEmployee->user->id,
            'user_id' => $user->id,
            'permission_level' => config('homas.authorizations.user'),
        ];

        $employee = (new AddUserToCompany)->execute($request);

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'user_id' => $user->id,
            'company_id' => $adminEmployee->company_id,
        ]);
    }

    /** @test */
    public function it_logs_an_action() : void
    {
        $adminEmployee = $this->createAdministrator();
        $user = factory(User::class)->create([]);

        $request = [
            'company_id' => $adminEmployee->company_id,
            'author_id' => $adminEmployee->user->id,
            'user_id' => $user->id,
            'permission_level' => config('homas.authorizations.user'),
        ];

        $employee = (new AddUserToCompany)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'user_added_to_company',
            'objects' => json_encode([
                'author_id' => $adminEmployee->user->id,
                'author_name' => $adminEmployee->user->name,
                'user_id' => $employee->user->id,
                'user_email' => $employee->user->email,
            ]),
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $adminEmployee = $this->createAdministrator();
        factory(User::class)->create([]);

        $request = [
            'company_id' => $adminEmployee->company_id,
            'author_id' => $adminEmployee->user->id,
        ];

        $this->expectException(ValidationException::class);
        (new AddUserToCompany)->execute($request);
    }
}
