<?php

namespace Tests\Unit\Services\Company\Employee\Manager;

use Tests\TestCase;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\DirectReport;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Manager\UnassignManager;

class UnassignManagerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_unassigns_a_manager() : void
    {
        $directReport = factory(DirectReport::class)->create([]);

        $request = [
            'company_id' => $directReport->directReport->company_id,
            'author_id' => $directReport->directReport->user->id,
            'employee_id' => $directReport->directReport->id,
            'manager_id' => $directReport->manager->id,
        ];

        $manager = (new UnassignManager)->execute($request);

        $this->assertDatabaseMissing('direct_reports', [
            'company_id' => $directReport->directReport->company_id,
            'employee_id' => $directReport->directReport->id,
            'manager_id' => $directReport->manager->id,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $manager
        );
    }

    /** @test */
    public function it_logs_an_action() : void
    {
        $directReport = factory(DirectReport::class)->create([]);

        $request = [
            'company_id' => $directReport->directReport->company_id,
            'author_id' => $directReport->directReport->user->id,
            'employee_id' => $directReport->directReport->id,
            'manager_id' => $directReport->manager->id,
        ];

        $manager = (new UnassignManager)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $directReport->directReport->company_id,
            'action' => 'manager_unassigned',
        ]);

        $this->assertDatabaseHas('employee_logs', [
            'company_id' => $directReport->directReport->company_id,
            'employee_id' => $directReport->directReport->id,
            'action' => 'manager_unassigned',
        ]);

        $this->assertDatabaseHas('employee_logs', [
            'company_id' => $directReport->directReport->company_id,
            'employee_id' => $manager->id,
            'action' => 'direct_report_unassigned',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new UnassignManager)->execute($request);
    }

    /** @test */
    public function it_fails_if_employee_and_manager_are_not_in_the_same_account() : void
    {
        $company = factory(Company::class)->create([]);
        $employee = factory(Employee::class)->create([
            'company_id' => $company->id,
        ]);
        $manager = factory(Employee::class)->create([
            'company_id' => $company->id,
        ]);

        factory(DirectReport::class)->create([
            'employee_id' => $employee->id,
            'manager_id' => $manager->id,
        ]);

        $request = [
            'company_id' => $company->id,
            'author_id' => $employee->user->id,
            'employee_id' => $employee->id,
            'manager_id' => $manager->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new UnassignManager)->execute($request);
    }
}
