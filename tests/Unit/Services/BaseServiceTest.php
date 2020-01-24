<?php

namespace Tests\Unit\Services;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\Team;
use App\Services\BaseService;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BaseServiceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_an_empty_rule_array(): void
    {
        $stub = $this->getMockForAbstractClass(BaseService::class);

        $this->assertIsArray(
            $stub->rules()
        );
    }

    /** @test */
    public function it_validates_rules(): void
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
    public function it_validates_that_the_employee_belongs_to_the_company(): void
    {
        $dunder = factory(Company::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $dunder->id,
        ]);

        $stub = $this->getMockForAbstractClass(BaseService::class);
        $michael = $stub->validateEmployeeBelongsToCompany([
            'employee_id' => $michael->id,
            'company_id' => $dunder->id,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $michael
        );

        // it throws an exception if the employee doesn't belong to the company
        $dunder = factory(Company::class)->create([]);
        $michael = factory(Employee::class)->create([]);

        $stub = $this->getMockForAbstractClass(BaseService::class);

        $this->expectException(ModelNotFoundException::class);
        $michael = $stub->validateEmployeeBelongsToCompany([
            'employee_id' => $michael->id,
            'company_id' => $dunder->id,
        ]);
    }

    /** @test */
    public function it_validates_that_the_team_belongs_to_the_company(): void
    {
        $dunder = factory(Company::class)->create([]);
        $sales = factory(Team::class)->create([
            'company_id' => $dunder->id,
        ]);

        $stub = $this->getMockForAbstractClass(BaseService::class);
        $sales = $stub->validateTeamBelongsToCompany([
            'team_id' => $sales->id,
            'company_id' => $dunder->id,
        ]);

        $this->assertInstanceOf(
            Team::class,
            $sales
        );

        // it throws an exception if the employee doesn't belong to the company
        $dunder = factory(Company::class)->create([]);
        $sales = factory(Team::class)->create([]);

        $stub = $this->getMockForAbstractClass(BaseService::class);

        $this->expectException(ModelNotFoundException::class);
        $stub->validateTeamBelongsToCompany([
            'team_id' => $sales->id,
            'company_id' => $dunder->id,
        ]);
    }

    /** @test */
    public function it_validates_permission_level(): void
    {
        // administrator has all rights
        $stub = $this->getMockForAbstractClass(BaseService::class);
        $michael = factory(Employee::class)->create([
            'permission_level' => config('officelife.authorizations.administrator'),
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $stub->validatePermissions($michael->id, $michael->company_id, config('officelife.authorizations.administrator'))
        );

        // test that an HR can't do an action reserved for an administrator
        $michael = factory(Employee::class)->create([
            'permission_level' => config('officelife.authorizations.hr'),
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $stub->validatePermissions($michael->id, $michael->company_id, config('officelife.authorizations.administrator'));

        // test that an user can't do an action reserved for an administrator
        $michael = factory(Employee::class)->create([
            'permission_level' => config('officelife.authorizations.user'),
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $stub->validatePermissions($michael->id, $michael->company_id, config('officelife.authorizations.administrator'));

        // test that a user can modify his own data regardless of his permission
        // level
        $michael = factory(Employee::class)->create([
            'permission_level' => config('officelife.authorizations.user'),
        ]);

        $this->assertInstanceOf(
            User::class,
            $stub->validatePermissions($michael->id, $michael->company_id, config('officelife.authorizations.administrator'), $michael->id)
        );

        // test that it returns an exception if the company and the employee
        // doesn't match
        $michael = factory(Employee::class)->create([
            'permission_level' => config('officelife.authorizations.user'),
        ]);
        $dunder = factory(Company::class)->create([]);

        $this->assertInstanceOf(
            User::class,
            $stub->validatePermissions($michael->id, $dunder->id, config('officelife.authorizations.user'))
        );
    }

    /** @test */
    public function it_returns_null_or_the_actual_value(): void
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
    public function it_returns_null_or_the_actual_date(): void
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
    public function it_returns_the_default_value_or_the_given_value(): void
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
