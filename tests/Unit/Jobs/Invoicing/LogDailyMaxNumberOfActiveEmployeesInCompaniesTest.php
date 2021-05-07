<?php

namespace Tests\Unit\Jobs\Invoicing;

use Tests\TestCase;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Jobs\Invoicing\LogDailyMaxNumberOfActiveEmployeesInCompanies;

class LogDailyMaxNumberOfActiveEmployeesInCompaniesTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_the_number_of_employees_who_are_not_locked_in_all_the_companies_in_the_instance(): void
    {
        $companyA = Company::factory()->create();
        $companyB = Company::factory()->create();

        Employee::factory()->count(3)->create([
            'company_id' => $companyA->id,
            'locked' => false,
        ]);

        // this is because the factories create too many Company objects
        Company::where('id', '!=', $companyA->id)->where('id', '!=', $companyB->id)->delete();

        LogDailyMaxNumberOfActiveEmployeesInCompanies::dispatch();

        $this->assertDatabaseHas('company_usage_history', [
            'company_id' => $companyA->id,
            'number_of_active_employees' => 3,
        ]);

        $this->assertDatabaseHas('company_usage_history', [
            'company_id' => $companyB->id,
            'number_of_active_employees' => 0,
        ]);
    }
}
