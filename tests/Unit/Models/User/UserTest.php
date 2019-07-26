<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\User\Notification;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_has_many_employees() : void
    {
        $user = factory(User::class)->create([]);
        factory(Employee::class, 3)->create([
            'user_id' => $user->id,
        ]);
        $this->assertTrue($user->employees()->exists());
    }

    /** @test */
    public function it_has_many_notifications() : void
    {
        $user = factory(User::class)->create([]);
        factory(Notification::class, 3)->create([
            'user_id' => $user->id,
        ]);
        $this->assertTrue($user->notifications()->exists());
    }

    /** @test */
    public function it_returns_the_name_attribute() : void
    {
        $user = new User;
        $user->email = 'dwight@dundermifflin.com';

        $this->assertEquals(
            $user->name,
            'dwight@dundermifflin.com'
        );

        $user->first_name = 'Dwight';

        $this->assertEquals(
            $user->name,
            'Dwight'
        );

        $user->last_name = 'Schrute';

        $this->assertEquals(
            $user->name,
            'Dwight Schrute'
        );
    }

    /** @test */
    public function it_gets_the_path_for_the_confirmation_link() : void
    {
        $user = factory(User::class)->create([
            'verification_link' => 'dunder',
        ]);

        $this->assertEquals(
            config('app.url').'/register/confirm/dunder',
            $user->getPathConfirmationLink()
        );
    }

    /** @test */
    public function it_gets_the_employee_object_for_the_given_user() : void
    {
        $employee = factory(Employee::class)->create([]);

        $this->assertInstanceOf(
            Employee::class,
            $employee->user->getEmployeeObjectForCompany($employee->company)
        );
    }

    /** @test */
    public function it_fails_to_get_the_employee_object_is_user_is_not_part_of_the_company() : void
    {
        $employee = factory(Employee::class)->create([]);
        $company = factory(Company::class)->create([]);

        $this->assertNull(
            $employee->user->getEmployeeObjectForCompany($company)
        );
    }
}
