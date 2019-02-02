<?php

namespace Tests;

use App\Models\User\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Check if the given route is accessible by a user with the given
     * permission.
     *
     * @param string $permissionLevel
     * @param string $route
     * @param int $statusCode
     */
    public function accessibleBy($permissionLevel, $route, $statusCode)
    {
        $role = factory(User::class)->create([
            'permission_level' => $permissionLevel,
        ]);
        $this->be($role);

        $response = $this->get(tenant($route));
        $response->assertStatus($statusCode);
    }
}
