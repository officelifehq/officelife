<?php

namespace Tests\Unit\Services\Company\Company;

use Tests\TestCase;
use App\Models\Company\Company;
use App\Models\Company\AuditLog;
use App\Services\Adminland\Company\LogAction;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_an_action()
    {
        $company = factory(Company::class)->create([]);

        $request = [
            'company_id' => $company->id,
            'action' => 'account_created',
            'objects' => '{"user": 1}',
        ];

        $auditLog = (new LogAction)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'id' => $auditLog->id,
            'company_id' => $company->id,
            'action' => 'account_created',
            'objects' => '{"user": 1}',
        ]);

        $this->assertInstanceOf(
            AuditLog::class,
            $auditLog
        );
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'action' => 'account_created',
        ];

        $this->expectException(ValidationException::class);
        (new LogAction)->execute($request);
    }
}
