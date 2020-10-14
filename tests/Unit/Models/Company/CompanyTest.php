<?php

namespace Tests\Unit\Models\Company;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Flow;
use App\Models\Company\Team;
use App\Models\Company\Skill;
use App\Models\Company\Company;
use App\Models\Company\Expense;
use App\Models\Company\Project;
use App\Models\Company\AuditLog;
use App\Models\Company\Employee;
use App\Models\Company\Hardware;
use App\Models\Company\Position;
use App\Models\Company\Question;
use App\Models\Company\CompanyNews;
use App\Models\Company\DirectReport;
use App\Models\Company\EmployeeStatus;
use App\Models\Company\ExpenseCategory;
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
    public function it_has_many_flows(): void
    {
        $company = factory(Company::class)->create();
        factory(Flow::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->flows()->exists());
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
    public function it_has_many_questions(): void
    {
        $company = factory(Company::class)->create();
        factory(Question::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->questions()->exists());
    }

    /** @test */
    public function it_has_many_hardware(): void
    {
        $company = factory(Company::class)->create();
        factory(Hardware::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->hardware()->exists());
    }

    /** @test */
    public function it_has_many_skills(): void
    {
        $company = factory(Company::class)->create();
        factory(Skill::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->skills()->exists());
    }

    /** @test */
    public function it_has_many_expenses(): void
    {
        $company = factory(Company::class)->create();
        factory(Expense::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->expenses()->exists());
    }

    /** @test */
    public function it_has_many_expense_categories(): void
    {
        $company = factory(Company::class)->create();
        factory(ExpenseCategory::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->expenseCategories()->exists());
    }

    /** @test */
    public function it_has_many_managers(): void
    {
        $company = factory(Company::class)->create();
        factory(DirectReport::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->managers()->exists());
    }

    /** @test */
    public function it_has_many_projects(): void
    {
        $company = factory(Company::class)->create();
        factory(Project::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->projects()->exists());
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

    /** @test */
    public function it_gets_the_list_of_the_employees_managers(): void
    {
        $company = factory(company::class)->create([]);
        $dwight = factory(Employee::class)->create([
            'company_id' => $company->id,
        ]);
        $john = factory(Employee::class)->create([
            'company_id' => $company->id,
        ]);
        factory(DirectReport::class)->create([
            'company_id' => $company->id,
            'manager_id' => $dwight->id,
        ]);
        factory(DirectReport::class, 2)->create([
            'company_id' => $company->id,
            'manager_id' => $john->id,
        ]);

        $this->assertEquals(
            2,
            $company->getListOfManagers()->count()
        );
    }
}
