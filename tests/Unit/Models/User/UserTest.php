<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification as FacadesNotification;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_has_many_employees(): void
    {
        $user = User::factory()->create([]);
        Employee::factory(3)->create([
            'user_id' => $user->id,
        ]);
        $this->assertTrue($user->employees()->exists());
    }

    /** @test */
    public function it_returns_the_name_attribute(): void
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
    public function it_gets_the_employee_object_for_the_given_user(): void
    {
        $dwight = Employee::factory()->create([]);

        $this->assertInstanceOf(
            Employee::class,
            $dwight->user->getEmployeeObjectForCompany($dwight->company)
        );
    }

    /** @test */
    public function it_fails_to_get_the_employee_object_is_user_is_not_part_of_the_company(): void
    {
        $dwight = Employee::factory()->create([]);
        $company = Company::factory()->create([]);

        $this->assertNull(
            $dwight->user->getEmployeeObjectForCompany($company)
        );
    }

    /** @test */
    public function it_sends_a_verification_email()
    {
        FacadesNotification::fake();

        // be sure to have at least 2 users
        User::factory()->create([]);
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $user->sendEmailVerificationNotification();

        FacadesNotification::assertSentTo(
            [$user],
            VerifyEmail::class
        );
    }
}
