<?php

namespace Tests\Unit\Jobs;

use App\Jobs\LogEmployeeAudit;
use Carbon\Carbon;
use Tests\TestCase;

class LogEmployeeAuditTest extends TestCase
{
    /** @test */
    public function it_logs_an_employee_audit(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $date = Carbon::now();

        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'action' => 'employee_status_created',
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'audited_at' => $date,
            'objects' => json_encode([
                'company_name' => $michael->company->name,
            ]),
        ];

        LogEmployeeAudit::dispatch($request);

        $this->assertDatabaseHas('employee_logs', [
            'employee_id' => $michael->id,
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
