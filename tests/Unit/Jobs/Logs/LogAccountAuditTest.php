<?php

namespace Tests\Unit\Jobs\Logs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\Logs\LogAccountAudit;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogAccountAuditTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_an_account_audit() : void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $michael->company_id,
            'action' => 'employee_status_created',
            'objects' => json_encode([
                'author_id' => $michael->id,
                'author_name' => $michael->name,
                'company_name' => $michael->company->name,
            ]),
        ];

        LogAccountAudit::dispatch($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $michael->company_id,
            'action' => 'employee_status_created',
            'objects' => json_encode([
                'author_id' => $michael->id,
                'author_name' => $michael->name,
                'company_name' => $michael->company->name,
            ]),
        ]);
    }
}
