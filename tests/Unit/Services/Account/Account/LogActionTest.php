<?php

namespace Tests\Unit\Services\Account\Account;

use Tests\TestCase;
use App\Models\Account\Account;
use App\Models\Account\AuditLog;
use App\Services\Account\Account\LogAction;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_an_action()
    {
        $account = factory(Account::class)->create([]);

        $request = [
            'account_id' => $account->id,
            'action' => 'account_created',
            'objects' => '{"user": 1}',
        ];

        $auditLog = (new LogAction)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'id' => $auditLog->id,
            'account_id' => $account->id,
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
            'subdomain' => 'dundermifflin',
        ];

        $this->expectException(ValidationException::class);
        (new LogAction)->execute($request);
    }
}
