<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\LogHelper;
use App\Models\Company\Team;
use App\Models\Company\TeamLog;
use App\Models\Company\AuditLog;
use App\Models\Company\EmployeeLog;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_the_string_explaining_the_audit_log(): void
    {
        $michael = $this->createAdministrator();

        $log = factory(AuditLog::class)->create([
            'action' => 'employee_invited_to_become_user',
            'objects' => json_encode([
                'author_id' => $michael->user->id,
                'employee_first_name' => $michael->user->firstname,
                'employee_last_name' => $michael->user->lastname,
            ]),
            'company_id' => $michael->company_id,
        ]);

        $this->assertIsString(LogHelper::processAuditLog($log));
    }

    /** @test */
    public function it_returns_the_string_explaining_the_employee_log(): void
    {
        $michael = $this->createAdministrator();

        $log = factory(EmployeeLog::class)->create([
            'action' => 'direct_report_assigned',
            'objects' => json_encode([
                'author_id' => $michael->user->id,
                'direct_report_name' => $michael->user->name,
            ]),
            'employee_id' => $michael->id,
        ]);

        $this->assertIsString(LogHelper::processEmployeeLog($log));
    }

    /** @test */
    public function it_returns_the_string_explaining_the_team_log(): void
    {
        $team = factory(Team::class)->create([]);

        $log = factory(TeamLog::class)->create([
            'action' => 'team_log_team_created',
            'objects' => json_encode([
                'team_name' => $team->id,
            ]),
            'team_id' => $team->id,
        ]);

        $this->assertIsString(LogHelper::processTeamLog($log));
    }

    /** @test */
    public function it_returns_empty_by_default_for_audit_log(): void
    {
        $log = factory(AuditLog::class)->create([
            'action' => '',
        ]);

        $string = LogHelper::processAuditLog($log);

        $this->assertIsString($string);

        $this->assertEquals('', $string);
    }

    /** @test */
    public function it_returns_empty_by_default_for_employee_log(): void
    {
        $log = factory(EmployeeLog::class)->create([
            'action' => '',
        ]);

        $string = LogHelper::processEmployeeLog($log);

        $this->assertIsString($string);

        $this->assertEquals('', $string);
    }

    /** @test */
    public function it_returns_empty_by_default_for_team_log(): void
    {
        $log = factory(TeamLog::class)->create([
            'action' => '',
        ]);

        $string = LogHelper::processTeamLog($log);

        $this->assertIsString($string);

        $this->assertEquals('', $string);
    }
}
