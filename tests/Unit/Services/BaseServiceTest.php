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
    public function it_returns_an_empty_rule_array() : void
    {
        $stub = $this->getMockForAbstractClass(BaseService::class);

        $this->assertIsArray(
            $stub->rules()
        );
    }

    /** @test */
    public function it_validates_rules() : void
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
    public function it_validates_permission_level() : void
    {
        // administrator has all rights
        $stub = $this->getMockForAbstractClass(BaseService::class);
        $employee = factory(Employee::class)->create([
            'permission_level' => config('villagers.authorizations.administrator'),
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $stub->validatePermissions($employee->id, $employee->company_id, config('villagers.authorizations.administrator'))
        );

        // test that an HR can't do an action reserved for an administrator
        $employee = factory(Employee::class)->create([
            'permission_level' => config('villagers.authorizations.hr'),
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $stub->validatePermissions($employee->id, $employee->company_id, config('villagers.authorizations.administrator'));

        // test that an user can't do an action reserved for an administrator
        $employee = factory(Employee::class)->create([
            'permission_level' => config('villagers.authorizations.user'),
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $stub->validatePermissions($employee->id, $employee->company_id, config('villagers.authorizations.administrator'));

        // test that a user can modify his own data regardless of his permission
        // level
        $employee = factory(Employee::class)->create([
            'permission_level' => config('villagers.authorizations.user'),
        ]);

        $this->assertInstanceOf(
            User::class,
            $stub->validatePermissions($employee->id, $employee->company_id, config('villagers.authorizations.administrator'), $employee->id)
        );
    }

    /** @test */
    public function it_returns_null_or_the_actual_value() : void
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
    public function it_returns_null_or_the_actual_date() : void
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
    public function it_returns_the_default_value_or_the_given_value() : void
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
