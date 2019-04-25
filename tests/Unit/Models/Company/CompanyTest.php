<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Company;
use App\Models\Company\AuditLog;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\EmployeeEvent;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_has_many_employees()
    {
        $company = factory(Company::class)->create();
        factory(Employee::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->employees()->exists());
    }

    /** @test */
    public function it_has_many_logs()
    {
        $company = factory(Company::class)->create();
        factory(AuditLog::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->logs()->exists());
    }

    /** @test */
    public function it_has_many_teams()
    {
        $company = factory(Company::class)->create();
        factory(Team::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->teams()->exists());
    }

    /** @test */
    public function it_has_many_positions()
    {
        $company = factory(Company::class)->create();
        factory(Position::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->positions()->exists());
    }

    /** @test */
    public function it_has_many_employee_events()
    {
        $company = factory(Company::class)->create();
        factory(EmployeeEvent::class, 2)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($company->employeeEvents()->exists());
    }
}
