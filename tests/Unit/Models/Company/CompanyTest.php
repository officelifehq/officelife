<?php

namespace Tests\Unit\Models\User;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Flow;
use App\Models\Company\Task;
use App\Models\Company\Team;
use App\Models\Company\Company;
use App\Models\Company\AuditLog;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\CompanyNews;
use App\Models\Company\EmployeeEvent;
use App\Models\Company\EmployeeStatus;
use App\Models\Company\CompanyPTOPolicy;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_has_many_employees(): void
    {
        $company = factory(Company::class)->create();
        factory(Employee::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->employees()->exists());
    }

    /** @test */
    public function it_has_many_logs(): void
    {
        $company = factory(Company::class)->create();
        factory(AuditLog::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->logs()->exists());
    }

    /** @test */
    public function it_has_many_teams(): void
    {
        $company = factory(Company::class)->create();
        factory(Team::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->teams()->exists());
    }

    /** @test */
    public function it_has_many_positions(): void
    {
        $company = factory(Company::class)->create();
        factory(Position::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->positions()->exists());
    }

    /** @test */
    public function it_has_many_employee_events(): void
    {
        $company = factory(Company::class)->create();
        factory(EmployeeEvent::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->employeeEvents()->exists());
    }

    /** @test */
    public function it_has_many_flows(): void
    {
        $company = factory(Company::class)->create();
        factory(Flow::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->flows()->exists());
    }

    /** @test */
    public function it_has_many_tasks(): void
    {
        $company = factory(Company::class)->create();
        factory(Task::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->tasks()->exists());
    }

    /** @test */
    public function it_has_many_statuses(): void
    {
        $company = factory(Company::class)->create();
        factory(EmployeeStatus::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->employeeStatuses()->exists());
    }

    /** @test */
    public function it_has_many_news(): void
    {
        $company = factory(Company::class)->create();
        factory(CompanyNews::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->news()->exists());
    }

    /** @test */
    public function it_has_many_pto_policies(): void
    {
        $company = factory(Company::class)->create();
        factory(CompanyPTOPolicy::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->ptoPolicies()->exists());
    }

    /** @test */
    public function it_returns_the_pto_policy_for_the_current_year(): void
    {
        Carbon::setTestNow(Carbon::create(2020, 1, 1));

        $company = factory(Company::class)->create();
        $firstPTO = factory(CompanyPTOPolicy::class)->create([
            'company_id' => $company->id,
            'year' => 2020,
        ]);
        factory(CompanyPTOPolicy::class)->create([
            'company_id' => $company->id,
            'year' => 2021,
        ]);

        $this->assertEquals(
            $firstPTO->id,
            $company->getCurrentPTOPolicy()->id
        );
    }
}
