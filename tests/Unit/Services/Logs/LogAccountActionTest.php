<?php

namespace Tests\Unit\Services\Logs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Company;
use App\Models\Company\AuditLog;
use App\Models\Company\Employee;
use App\Services\Logs\LogAccountAction;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LogAccountActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_an_action(): void
    {
        $company = Company::factory()->create([]);
        $michael = Employee::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->executeService($michael, $company);
    }

    /** @test */
    public function it_fails_if_the_author_is_not_in_the_company(): void
    {
        $company = Company::factory()->create([]);
        $michael = Employee::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $company);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'action' => 'account_created',
        ];

        $this->expectException(ValidationException::class);
        (new LogAccountAction)->execute($request);
    }

    private function executeService(Employee $michael, Company $company): void
    {
        $date = Carbon::now();

        $request = [
            'company_id' => $company->id,
            'action' => 'account_created',
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'audited_at' => $date,
            'objects' => '{"user": 1}',
        ];

        $auditLog = (new LogAccountAction)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'id' => $auditLog->id,
            'company_id' => $company->id,
            'action' => 'account_created',
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'audited_at' => $date,
            'objects' => '{"user": 1}',
        ]);

        $this->assertInstanceOf(
            AuditLog::class,
            $auditLog
        );
    }
}
