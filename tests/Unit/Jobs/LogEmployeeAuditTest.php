<?php

namespace Tests\Unit\Jobs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\LogEmployeeAudit;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogEmployeeAuditTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_an_employee_audit() : void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'action' => 'employee_status_created',
            'objects' => json_encode([
                'author_id' => $michael->id,
                'author_name' => $michael->name,
                'company_name' => $michael->company->name,
            ]),
        ];

        LogEmployeeAudit::dispatch($request);

        $this->assertDatabaseHas('employee_logs', [
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'action' => 'employee_status_created',
            'objects' => json_encode([
                'author_id' => $michael->id,
                'author_name' => $michael->name,
                'company_name' => $michael->company->name,
            ]),
        ]);
    }
}
