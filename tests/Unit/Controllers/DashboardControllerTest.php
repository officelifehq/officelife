<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User\User;

class DashboardControllerTest extends TestCase
{
    /** @test */
    public function it_loads_the_dashboard()
    {
        $user = factory(User::class)->create([]);
        $this->be($user);

        $response = $this->get($user->account_id.'/dashboard');
        $response->assertStatus(200);
    }
}
