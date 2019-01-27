<?php

namespace Tests\Unit\Services;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User\User;
use App\Services\BaseService;
use App\Exceptions\NotEnoughPermissionException;

class BaseServiceTest extends TestCase
{
    /** @test */
    public function it_returns_an_empty_rule_array()
    {
        $stub = $this->getMockForAbstractClass(BaseService::class);

        $this->assertInternalType(
            'array',
            $stub->rules()
        );
    }

    /** @test */
    public function it_validates_rules()
    {
        $rules = [
            'street' => 'nullable|string|max:255',
        ];
        $stub = $this->getMockForAbstractClass(BaseService::class);
        $stub->rules([$rules]);

        $this->assertTrue(
            $stub->validate([
                'street' => 'la rue du bonheur',
            ])
        );
    }

    /** @test */
    public function it_validates_permission_level()
    {
        // administrator has all rights
        $stub = $this->getMockForAbstractClass(BaseService::class);
        $user = factory(User::class)->create([
            'permission_level' => config('homas.authorizations.administrator'),
        ]);

        $this->assertTrue(
            $stub->validatePermissions($user->id, 'administrator')
        );

        $user = factory(User::class)->create([
            'permission_level' => config('homas.authorizations.hr'),
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $stub->validatePermissions($user->id, 'administrator');
    }

    /** @test */
    public function it_returns_null_or_the_actual_value()
    {
        $stub = $this->getMockForAbstractClass(BaseService::class);
        $array = [
            'value' => 'this',
        ];

        $this->assertEquals(
            'this',
            $stub->nullOrValue($array, 'value')
        );

        $array = [
            'otherValue' => '',
        ];

        $this->assertNull(
            $stub->nullOrValue($array, 'otherValue')
        );

        $array = [];

        $this->assertNull(
            $stub->nullOrValue($array, 'value')
        );
    }

    /** @test */
    public function it_returns_null_or_the_actual_date()
    {
        $stub = $this->getMockForAbstractClass(BaseService::class);
        $array = [
            'value' => '1990-01-01',
        ];

        $this->assertInstanceOf(
            Carbon::class,
            $stub->nullOrDate($array, 'value')
        );

        $array = [
            'otherValue' => '',
        ];

        $this->assertNull(
            $stub->nullOrDate($array, 'otherValue')
        );

        $array = [];

        $this->assertNull(
            $stub->nullOrDate($array, 'value')
        );
    }
}
