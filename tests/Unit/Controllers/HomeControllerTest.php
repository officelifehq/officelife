<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    /** @test */
    public function it_loads_the_dashboard()
    {
        $admin = $this->createAdministrator();
        $this->be($admin->user);

        $response = $this->get('/home');
        $response->assertStatus(200);
    }
}
