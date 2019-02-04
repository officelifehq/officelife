<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\Company\Company;
use App\Models\Company\AuditLog;
use App\Models\Company\Employee;
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
}
