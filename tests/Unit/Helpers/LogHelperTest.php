<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\LogHelper;
use App\Models\Company\AuditLog;
use App\Models\Company\EmployeeLog;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_the_string_explaining_the_audit_log() : void
    {
        $adminEmployee = $this->createAdministrator();

        $auditLog = factory(AuditLog::class)->create([
            'action' => 'employee_invited_to_become_user',
            'objects' => json_encode([
                'author_id' => $adminEmployee->user->id,
                'employee_name' => $adminEmployee->user->name,
            ]),
            'company_id' => $adminEmployee->company_id,
        ]);

        $this->assertIsString(LogHelper::processAuditLog($auditLog));
    }

    /** @test */
    public function it_returns_the_string_explaining_the_employee_log() :void
    {
        $adminEmployee = $this->createAdministrator();

        $auditLog = factory(EmployeeLog::class)->create([
            'action' => 'direct_report_assigned',
            'objects' => json_encode([
                'author_id' => $adminEmployee->user->id,
                'direct_report_name' => $adminEmployee->user->name,
            ]),
            'employee_id' => $adminEmployee->id,
        ]);

        $this->assertIsString(LogHelper::processEmployeeLog($auditLog));
    }
}
