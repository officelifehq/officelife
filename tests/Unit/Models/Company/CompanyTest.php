<?php

namespace Tests\Unit\Models\Company;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\File;
use App\Models\Company\Flow;
use App\Models\Company\Team;
use App\Models\Company\Wiki;
use App\Models\Company\Group;
use App\Models\Company\Skill;
use App\Models\Company\Comment;
use App\Models\Company\Company;
use App\Models\Company\ECoffee;
use App\Models\Company\Expense;
use App\Models\Company\Project;
use App\Models\Company\AuditLog;
use App\Models\Company\Employee;
use App\Models\Company\Hardware;
use App\Models\Company\Position;
use App\Models\Company\Question;
use App\Models\Company\Software;
use App\Models\Company\Candidate;
use App\Models\Company\ImportJob;
use App\Models\Company\IssueType;
use App\Models\Company\Timesheet;
use App\Models\Company\JobOpening;
use App\Models\Company\CompanyNews;
use App\Models\Company\DirectReport;
use App\Models\Company\CompanyInvoice;
use App\Models\Company\ConsultantRate;
use App\Models\Company\DisciplineCase;
use App\Models\Company\EmployeeStatus;
use App\Models\Company\ExpenseCategory;
use App\Models\Company\CompanyPTOPolicy;
use App\Models\Company\AskMeAnythingSession;
use App\Models\Company\RecruitingStageTemplate;
use App\Models\Company\CompanyDailyUsageHistory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_has_many_employees(): void
    {
        $company = Company::factory()->create();
        Employee::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->employees()->exists());
    }

    /** @test */
    public function it_has_many_logs(): void
    {
        $company = Company::factory()->create();
        AuditLog::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->logs()->exists());
    }

    /** @test */
    public function it_has_many_teams(): void
    {
        $company = Company::factory()->create();
        Team::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->teams()->exists());
    }

    /** @test */
    public function it_has_many_positions(): void
    {
        $company = Company::factory()->create();
        Position::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->positions()->exists());
    }

    /** @test */
    public function it_has_many_flows(): void
    {
        $company = Company::factory()->create();
        Flow::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->flows()->exists());
    }

    /** @test */
    public function it_has_many_statuses(): void
    {
        $company = Company::factory()->create();
        EmployeeStatus::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->employeeStatuses()->exists());
    }

    /** @test */
    public function it_has_many_news(): void
    {
        $company = Company::factory()->create();
        CompanyNews::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->news()->exists());
    }

    /** @test */
    public function it_has_many_pto_policies(): void
    {
        $company = Company::factory()->create();
        CompanyPTOPolicy::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->ptoPolicies()->exists());
    }

    /** @test */
    public function it_has_many_questions(): void
    {
        $company = Company::factory()->create();
        Question::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->questions()->exists());
    }

    /** @test */
    public function it_has_many_hardware(): void
    {
        $company = Company::factory()->create();
        Hardware::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->hardware()->exists());
    }

    /** @test */
    public function it_has_many_skills(): void
    {
        $company = Company::factory()->create();
        Skill::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->skills()->exists());
    }

    /** @test */
    public function it_has_many_expenses(): void
    {
        $company = Company::factory()->create();
        Expense::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->expenses()->exists());
    }

    /** @test */
    public function it_has_many_expense_categories(): void
    {
        $company = Company::factory()->create();
        ExpenseCategory::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->expenseCategories()->exists());
    }

    /** @test */
    public function it_has_many_managers(): void
    {
        $company = Company::factory()->create();
        DirectReport::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->managers()->exists());
    }

    /** @test */
    public function it_has_many_projects(): void
    {
        $company = Company::factory()->create();
        Project::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->projects()->exists());
    }

    /** @test */
    public function it_has_many_timesheets(): void
    {
        $company = Company::factory()->create();
        Timesheet::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->timesheets()->exists());
    }

    /** @test */
    public function it_has_many_consultant_rates(): void
    {
        $company = Company::factory()->create();
        ConsultantRate::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->consultantRates()->exists());
    }

    /** @test */
    public function it_has_many_ecoffee_sessions(): void
    {
        $company = Company::factory()->create();
        ECoffee::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->eCoffees()->exists());
    }

    /** @test */
    public function it_has_many_import_jobs(): void
    {
        $company = Company::factory()->create();
        ImportJob::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->importJobs()->exists());
    }

    /** @test */
    public function it_has_many_groups(): void
    {
        $company = Company::factory()->create();
        Group::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->groups()->exists());
    }

    /** @test */
    public function it_has_many_company_usage_history(): void
    {
        $company = Company::factory()->create();
        CompanyDailyUsageHistory::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->usageHistory()->exists());
    }

    /** @test */
    public function it_has_many_company_invoices(): void
    {
        $company = Company::factory()->create();
        CompanyInvoice::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->invoices()->exists());
    }

    /** @test */
    public function it_has_many_softwares(): void
    {
        $company = Company::factory()->create();
        Software::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->softwares()->exists());
    }

    /** @test */
    public function it_has_many_wikis(): void
    {
        $company = Company::factory()->create();
        Wiki::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->wikis()->exists());
    }

    /** @test */
    public function it_has_many_recruiting_stage_templates(): void
    {
        $company = Company::factory()->create();
        RecruitingStageTemplate::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->recruitingStageTemplates()->exists());
    }

    /** @test */
    public function it_has_one_logo(): void
    {
        $file = File::factory()->create([]);
        $company = Company::factory()->create([
            'logo_file_id' => $file->id,
        ]);

        $this->assertTrue($company->logo()->exists());
    }

    /** @test */
    public function it_has_many_job_openings(): void
    {
        $company = Company::factory()->create();
        JobOpening::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->jobOpenings()->exists());
    }

    /** @test */
    public function it_has_many_candidates(): void
    {
        $company = Company::factory()->create();
        Candidate::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->candidates()->exists());
    }

    /** @test */
    public function it_has_many_ask_me_anything_sessions(): void
    {
        $company = Company::factory()->create();
        AskMeAnythingSession::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->askMeAnythingSessions()->exists());
    }

    /** @test */
    public function it_has_many_comments(): void
    {
        $company = Company::factory()->create();
        Comment::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->comments()->exists());
    }

    /** @test */
    public function it_has_many_issue_types(): void
    {
        $company = Company::factory()->create();
        IssueType::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->issueTypes()->exists());
    }

    /** @test */
    public function it_has_many_discipline_cases(): void
    {
        $company = Company::factory()->create();
        DisciplineCase::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->disciplineCases()->exists());
    }

    /** @test */
    public function it_returns_the_pto_policy_for_the_current_year(): void
    {
        Carbon::setTestNow(Carbon::create(2020, 1, 1));

        $company = Company::factory()->create();
        $firstPTO = CompanyPTOPolicy::factory()->create([
            'company_id' => $company->id,
            'year' => 2020,
        ]);
        CompanyPTOPolicy::factory()->create([
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
        $company = Company::factory()->create([]);
        $dwight = Employee::factory()->create([
            'company_id' => $company->id,
        ]);
        $john = Employee::factory()->create([
            'company_id' => $company->id,
        ]);
        DirectReport::factory()->create([
            'company_id' => $company->id,
            'manager_id' => $dwight->id,
        ]);
        DirectReport::factory()->count(2)->create([
            'company_id' => $company->id,
            'manager_id' => $john->id,
        ]);

        $this->assertEquals(
            2,
            $company->getListOfManagers()->count()
        );
    }
}
