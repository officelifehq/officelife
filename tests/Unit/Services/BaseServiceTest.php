<?php

namespace Tests\Unit\Services;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User\User;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BaseServiceTest extends TestCase
{
    use DatabaseTransactions;

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
        $employee = factory(Employee::class)->create([
            'permission_level' => config('homas.authorizations.administrator'),
        ]);

        $this->assertInstanceOf(
            User::class,
            $stub->validatePermissions($employee->user_id, $employee->company_id, config('homas.authorizations.administrator'))
        );

        // test that an HR can't do an action reserved for an administrator
        $employee = factory(Employee::class)->create([
            'permission_level' => config('homas.authorizations.hr'),
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $stub->validatePermissions($employee->user->id, $employee->company_id, config('homas.authorizations.administrator'));

        // test that an user can't do an action reserved for an administrator
        $employee = factory(Employee::class)->create([
            'permission_level' => config('homas.authorizations.user'),
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $stub->validatePermissions($employee->user->id, $employee->company_id, config('homas.authorizations.administrator'));

        // test that a user can modify his own data regardless of his permission
        // level
        $employee = factory(Employee::class)->create([
            'permission_level' => config('homas.authorizations.user'),
        ]);

        $this->assertInstanceOf(
            User::class,
            $stub->validatePermissions($employee->user_id, $employee->company_id, config('homas.authorizations.administrator'), $employee->id)
        );
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

    /** @test */
    public function it_returns_the_default_value_or_the_given_value()
    {
        $stub = $this->getMockForAbstractClass(BaseService::class);
        $array = [
            'value' => true,
        ];

        $this->assertTrue(
            $stub->valueOrFalse($array, 'value')
        );

        $array = [
            'value' => false,
        ];

        $this->assertFalse(
            $stub->valueOrFalse($array, 'value')
        );
    }
}
