<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\LogHelper;
use App\Models\Company\AuditLog;
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

        $this->assertIsString(LogHelper::processLog($auditLog));
    }
}
