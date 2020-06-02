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
            $stub->validateRules([
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
    public function it_validates_the_right_to_execute_the_service(): void
    {
        // administrator has all rights
        $stub = $this->getMockForAbstractClass(BaseService::class);
        $michael = factory(Employee::class)->create([
            'permission_level' => config('officelife.permission_level.administrator'),
        ]);

        $this->assertTrue(
            $stub->author($michael->id)
                ->inCompany($michael->company_id)
                ->asAtLeastAdministrator()
                ->canExecuteService()
        );

        $this->assertTrue(
            $stub->author($michael->id)
                ->inCompany($michael->company_id)
                ->asAtLeastHR()
                ->canExecuteService()
        );

        $this->assertTrue(
            $stub->author($michael->id)
                ->inCompany($michael->company_id)
                ->asNormalUser()
                ->canExecuteService()
        );

        // test that an HR can't do an action reserved for an administrator
        $michael = factory(Employee::class)->create([
            'permission_level' => config('officelife.permission_level.hr'),
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $stub->author($michael->id)
            ->inCompany($michael->company_id)
            ->asAtLeastAdministrator()
            ->canExecuteService();

        // test that an user can't do an action reserved for an administrator
        $michael = factory(Employee::class)->create([
            'permission_level' => config('officelife.permission_level.user'),
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $stub->author($michael->id)
            ->inCompany($michael->company_id)
            ->asAtLeastAdministrator()
            ->canExecuteService();

        // test that an user can't do an action reserved for an HR
        $michael = factory(Employee::class)->create([
            'permission_level' => config('officelife.permission_level.user'),
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $stub->author($michael->id)
            ->inCompany($michael->company_id)
            ->asAtLeastHR()
            ->canExecuteService();

        // test that a user can modify his own data regardless of his permission
        // level
        $michael = factory(Employee::class)->create([
            'permission_level' => config('officelife.permission_level.user'),
        ]);

        $this->assertTrue(
            $stub->author($michael->id)
                ->inCompany($michael->company_id)
                ->bypassPermissionLevel()
                ->canExecuteService()
        );

        // test that it returns an exception if the company and the employee
        // doesn't match
        $michael = factory(Employee::class)->create([]);
        $dunder = factory(Company::class)->create([]);

        $this->expectException(ModelNotFoundException::class);
        $stub->author($michael->id)
            ->inCompany($dunder->id)
            ->canExecuteService();
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
            $stub->valueOrNull($array, 'value')
        );

        $array = [
            'otherValue' => '',
        ];

        $this->assertNull(
            $stub->valueOrNull($array, 'otherValue')
        );

        $array = [];

        $this->assertNull(
            $stub->valueOrNull($array, 'value')
        );
    }

    /** @test */
    public function it_returns_the_given_date_or_the_current_date(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $stub = $this->getMockForAbstractClass(BaseService::class);
        $array = [
            'value' => '1990-01-01',
        ];

        $this->assertInstanceOf(
            Carbon::class,
            $stub->valueOrNow($array, 'value')
        );

        $this->assertEquals(
            '1990-01-01',
            $stub->valueOrNow($array, 'value')->format('Y-m-d')
        );

        $array = [
            'otherValue' => '',
        ];

        $this->assertEquals(
            '2018-01-01',
            $stub->valueOrNow($array, 'otherValue')->format('Y-m-d')
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
