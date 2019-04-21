<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchableTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_searches_contacts_and_return_collection()
    {
        $employee = factory(Employee::class)->create([]);
        $searchResults = Employee::search($employee->first_name, $employee->company_id, 10, 'created_at desc');

        $this->assertInstanceOf('Illuminate\Pagination\LengthAwarePaginator', $searchResults);
    }

    /** @test */
    public function it_searches_an_employee_through_the_first_name()
    {
        $employee = factory(Employee::class)->create([]);
        $searchResults = Employee::search($employee->first_name, $employee->company_id, 10, 'created_at desc');

        $this->assertTrue($searchResults->contains($employee));
    }

    /** @test */
    public function it_searches_an_employee_through_the_last_name()
    {
        $employee = factory(Employee::class)->create([]);
        $searchResults = Employee::search($employee->last_name, $employee->company_id, 10, 'created_at desc');

        $this->assertTrue($searchResults->contains($employee));
    }

    /** @test */
    public function it_fails_to_search_employees()
    {
        $employee = factory(Employee::class)->create(['first_name' => 'TestShouldFail']);
        $searchResults = Employee::search('TestWillSucceed', $employee->company_id, 10, 'created_at desc');

        $this->assertFalse($searchResults->contains($employee));
    }
}
