<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Account\Team;
use App\Models\Company\Company;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Company\Employee;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_company()
    {
        $user = factory(User::class)->create([]);
        $this->assertTrue($user->company()->exists());
    }

    /** @test */
    public function it_returns_the_name_attribute()
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
    public function it_gets_the_path_for_the_confirmation_link()
    {
        $user = factory(User::class)->create([
            'confirmation_link' => 'dunder',
        ]);

        $this->assertEquals(
            config('app.url') . '/register/confirm/dunder',
            $user->getPathConfirmationLink()
        );
    }

    /** @test */
    public function it_checks_if_the_user_is_part_of_the_company()
    {
        $employee = factory(Employee::class)->create([]);

        $this->assertTrue($employee->user->isPartOfCompany($employee->company));
    }
}
