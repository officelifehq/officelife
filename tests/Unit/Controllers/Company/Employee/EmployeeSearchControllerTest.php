<?php

namespace Tests\Unit\Controllers\Company\Employee;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\DirectReport;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeSearchControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_the_list_of_potential_managers_for_this_employee()
    {
        $employee = factory(Employee::class)->create([]);
        $this->be($employee->user);

        // build other employees, so there is currently 4 employees in the
        // account
        factory(Employee::class, 3)->create([
            'company_id' => $employee->company_id,
        ]);

        $response = $this->executeSearch($employee, 'dw');
        $response->assertStatus(200);

        // there should be only 3 employees as potential managers
        $this->assertCount(
            3,
            $response->decodeResponseJson()['data']
        );

        $response = $this->executeSearch($employee, 'dassdafasdw');
        $response->assertStatus(200);

        // there should be only 3 employees as potential managers
        $this->assertCount(
            0,
            $response->decodeResponseJson()['data']
        );

        // create 1 employee as the manager
        $manager = factory(Employee::class)->create([
            'company_id' => $employee->company_id,
        ]);
        factory(DirectReport::class)->create([
            'company_id' => $employee->company_id,
            'manager_id' => $manager->id,
            'employee_id' => $employee->id,
        ]);

        $response = $this->executeSearch($employee, 'dw');
        $response->assertStatus(200);

        // there should be still only 3 employees as potential managers
        $this->assertCount(
            3,
            $response->decodeResponseJson()['data']
        );

        // create 3 direct reports
        factory(DirectReport::class, 3)->create([
            'company_id' => $employee->company_id,
            'manager_id' => $employee->id,
        ]);

        $response = $this->executeSearch($employee, 'dw');
        $response->assertStatus(200);

        // there should be still only 3 employees as potential managers
        $this->assertCount(
            3,
            $response->decodeResponseJson()['data']
        );
    }

    private function executeSearch($employee, $searchTerm)
    {
        return $this->json('POST', '/'.$employee->company_id.'/employees/'.$employee->id.'/search/hierarchy', [
            'searchTerm' => $searchTerm,
        ]);
    }
}
