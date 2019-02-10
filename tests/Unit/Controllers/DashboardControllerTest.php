<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    /** @test */
    public function it_loads_the_dashboard()
    {
        $admin = $this->createAdministrator();
        $this->be($admin->user);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }
}
