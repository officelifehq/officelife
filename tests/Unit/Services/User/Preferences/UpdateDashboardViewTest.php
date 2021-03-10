<?php

namespace Tests\Unit\Services\User\Preferences;

use Tests\TestCase;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use App\Services\User\Preferences\UpdateDashboardView;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateDashboardViewTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_the_default_dashboard_view_parameter(): void
    {
        $employee = Employee::factory()->create();

        $request = [
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
            'view' => 'company',
        ];

        $bool = (new UpdateDashboardView)->execute($request);

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'default_dashboard_view' => 'company',
        ]);

        $this->assertTrue($bool);
    }

    /** @test */
    public function it_fails_when_the_employee_doesnt_belong_to_the_company(): void
    {
        $employee = Employee::factory()->create();
        $company = Company::factory()->create([]);

        $request = [
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'view' => 'company',
        ];

        $this->expectException(ModelNotFoundException::class);
        (new UpdateDashboardView)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'action' => 'account_created',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateDashboardView)->execute($request);
    }
}
