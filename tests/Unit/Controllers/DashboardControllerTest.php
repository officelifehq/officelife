<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User\User;

class DashboardControllerTest extends TestCase
{
    /** @test */
    public function it_loads_the_dashboard()
    {
        $admin = factory(User::class)->create([]);
        $this->be($admin);

        $response = $this->get(tenant('/dashboard'));
        $response->assertStatus(200);
    }
}
