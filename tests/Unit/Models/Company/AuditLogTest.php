<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\AuditLog;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuditLogTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $auditLog = factory(AuditLog::class)->create([]);
        $this->assertTrue($auditLog->company()->exists());
    }

    /** @test */
    public function it_returns_the_date_attribute(): void
    {
        $auditLog = factory(AuditLog::class)->create([
            'audited_at' => '2017-01-22 17:56:03',
        ]);
        $this->assertEquals(
            'Jan 22, 2017 17:56',
            $auditLog->date
        );
    }

    /** @test */
    public function it_returns_the_object_attribute(): void
    {
        $auditLog = factory(AuditLog::class)->create([]);
        $this->assertEquals(
            1,
            $auditLog->object->{'user'}
        );
    }

    /** @test */
    public function it_returns_the_content_attribute(): void
    {
        $michael = $this->createAdministrator();

        $auditLog = factory(AuditLog::class)->create([
            'action' => 'employee_invited_to_become_user',
            'objects' => json_encode([
                'author_id' => $michael->user->id,
                'employee_first_name' => $michael->user->firstname,
                'employee_last_name' => $michael->user->lastname,
            ]),
            'company_id' => $michael->company_id,
        ]);

        $this->assertEquals(
            'Sent an invitation to '.$michael->user->firstname.' '.$michael->user->lastname.' to join the company.',
            $auditLog->content
        );
    }
}
