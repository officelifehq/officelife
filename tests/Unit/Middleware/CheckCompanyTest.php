<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\Employee;

class CheckCompanyTest extends TestCase
{
    /** @test */
    public function it_makes_sure_user_cant_access_a_company_he_is_not_part_of()
    {
        $employee = factory(Employee::class)->create([]);

        $this->be($employee->user);

        $response = $this->get('/home');
        $response->assertStatus(200);

        $response = $this->get($employee->company->id.'/dashboard');
        $response->assertStatus(200);

        $otherUser = factory(User::class)->create([]);
        $this->be($otherUser);

        $response = $this->get('/home');
        $response->assertStatus(200);

        $response = $this->get($employee->company->id.'/dashboard');
        $response->assertStatus(401);
    }
}
