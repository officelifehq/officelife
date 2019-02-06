<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User\User;

class DashboardControllerTest extends TestCase
{
    /** @test */
    public function it_loads_the_dashboard()
    {
        $admin = factory(User::class)->create([]);
        $this->be($admin);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }
}
