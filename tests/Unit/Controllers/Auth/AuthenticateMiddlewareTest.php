<?php

namespace Tests\Unit\Controllers\Auth;

use Tests\TestCase;

class AuthenticateMiddlewareTest extends TestCase
{
    /** @test */
    public function it_redirects_to_login(): void
    {
        $response = $this->get('/home');

        $response->assertStatus(302);

        $response->assertRedirect('/login');
    }
}
