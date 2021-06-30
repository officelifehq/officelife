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
        config(['officelife.enable_paid_plan' => true]);
        $companyA = Company::factory()->create();
        $companyB = Company::factory()->create();

        $michael = Employee::factory()->create([
            'company_id' => $companyA->id,
            'locked' => false,
        ]);
        Employee::factory()->count(2)->create([
            'company_id' => $companyA->id,
            'locked' => false,
        ]);

        // this is because the factories create too many Company objects
        Company::where('id', '!=', $companyA->id)->where('id', '!=', $companyB->id)->delete();

        LogDailyMaxNumberOfActiveEmployeesInCompanies::dispatch();

        $this->assertDatabaseHas('company_daily_usage_history', [
            'company_id' => $companyA->id,
            'number_of_active_employees' => 3,
        ]);

        $this->assertDatabaseHas('company_daily_usage_history', [
            'company_id' => $companyB->id,
            'number_of_active_employees' => 0,
        ]);

        $this->assertDatabaseHas('company_usage_history_details', [
            'employee_name' => $michael->name,
            'employee_email' => $michael->email,
        ]);
    }

    /** @test */
    public function it_does_nothing_if_env_is_not_set(): void
    {
        config(['officelife.enable_paid_plan' => false]);
        LogDailyMaxNumberOfActiveEmployeesInCompanies::dispatch();

        $this->assertDatabaseMissing('company_daily_usage_history', [
            'number_of_active_employees' => 3,
        ]);
    }
}
