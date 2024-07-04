<?php

namespace Tests\Unit\Jobs;

use App\Jobs\LogAccountAudit;
use Carbon\Carbon;
use Tests\TestCase;

class LogAccountAuditTest extends TestCase
{
    /** @test */
    public function it_logs_an_account_audit(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $date = Carbon::now();

        $request = [
            'company_id' => $michael->company_id,
            'action' => 'employee_status_created',
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'audited_at' => $date,
            'objects' => json_encode([
                'company_name' => $michael->company->name,
            ]),
        ];

        LogAccountAudit::dispatch($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $michael->company_id,
            'action' => 'employee_status_created',
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'audited_at' => $date,
            'objects' => json_encode([
                'company_name' => $michael->company->name,
            ]),
        ]);
    }
}
