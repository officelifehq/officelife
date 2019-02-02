<?php

namespace Tests\Unit\Models\Account;

use Tests\ApiTestCase;
use App\Models\User\User;
use App\Models\Account\Team;
use App\Models\Account\AuditLog;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuditLogTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_account()
    {
        $auditLog = factory(AuditLog::class)->create([]);
        $this->assertTrue($auditLog->account()->exists());
    }

    /** @test */
    public function it_returns_the_date_attribute()
    {
        $auditLog = factory(AuditLog::class)->create([
            'created_at' => '2017-01-22 17:56:03',
        ]);
        $this->assertEquals(
            'Jan 22, 2017 17:56',
            $auditLog->date
        );
    }

    /** @test */
    public function it_returns_the_object_attribute()
    {
        $auditLog = factory(AuditLog::class)->create([]);
        $this->assertEquals(
            1,
            $auditLog->object->{'user'}
        );
    }

    /** @test */
    public function it_returns_the_author_attribute()
    {
        $user = $this->signIn();
        $auditLog = factory(AuditLog::class)->create([
            'objects' => json_encode([
                'author_id' => $user->id,
            ]),
            'account_id' => $user->account_id,
        ]);

        $this->assertEquals(
            '<a href="'.tenant('/employees/'.$user->id).'">'.$user->name.'</a>',
            $auditLog->author
        );

        $auditLog = factory(AuditLog::class)->create([
            'objects' => json_encode([
                'author_id' => 12345,
                'author_name' => 'Dwight Schrute',
            ]),
            'account_id' => $user->account_id,
        ]);

        $this->assertEquals(
            'Dwight Schrute',
            $auditLog->author
        );
    }

    /** @test */
    public function it_returns_the_team_attribute()
    {
        $user = $this->signIn();
        $team = factory(Team::class)->create([
            'account_id' => $user->account_id,
        ]);
        $auditLog = factory(AuditLog::class)->create([
            'objects' => json_encode([
                'team_id' => $team->id,
            ]),
            'account_id' => $team->account_id,
        ]);

        $this->assertEquals(
            '<a href="'.tenant('/teams/'.$team->id).'">'.$team->name.'</a>',
            $auditLog->team
        );

        $auditLog = factory(AuditLog::class)->create([
            'objects' => json_encode([
                'team_id' => 12345,
                'team_name' => 'Sales',
            ]),
            'account_id' => $team->account_id,
        ]);

        $this->assertEquals(
            'Sales',
            $auditLog->team
        );
    }

    /** @test */
    public function it_returns_the_user_attribute()
    {
        $user = $this->signIn();
        $auditLog = factory(AuditLog::class)->create([
            'objects' => json_encode([
                'user_id' => $user->id,
            ]),
            'account_id' => $user->account_id,
        ]);

        $this->assertEquals(
            '<a href="'.tenant('/employees/'.$user->id).'">'.$user->name.'</a>',
            $auditLog->user
        );

        $auditLog = factory(AuditLog::class)->create([
            'objects' => json_encode([
                'user_id' => 12345,
                'user_email' => 'dwight@dundermifflin.com',
            ]),
            'account_id' => $user->account_id,
        ]);

        $this->assertEquals(
            'dwight@dundermifflin.com',
            $auditLog->user
        );
    }
}
