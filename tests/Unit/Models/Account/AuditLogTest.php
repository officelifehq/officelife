<?php

namespace Tests\Unit\Models\Account;

use Tests\TestCase;
use App\Models\Account\AuditLog;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuditLogTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_account()
    {
        $auditLog = factory(AuditLog::class)->create([]);
        $this->assertTrue($auditLog->account()->exists());
    }
}
