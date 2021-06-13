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

        $log = AuditLog::factory()->create([
            'action' => 'employee_invited_to_become_user',
            'objects' => json_encode([
                'author_id' => $michael->user->id,
                'employee_first_name' => $michael->user->first_name,
                'employee_last_name' => $michael->user->last_name,
            ]),
            'company_id' => $michael->company_id,
        ]);

        $this->assertIsString(LogHelper::processAuditLog($log));
        $this->assertNotEquals(trans('account.log_employee_invited_to_become_user'), LogHelper::processAuditLog($log));
    }

    /** @test */
    public function it_returns_the_string_explaining_the_employee_log(): void
    {
        $michael = $this->createAdministrator();

        $log = EmployeeLog::factory()->create([
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
        $team = Team::factory()->create([
            'name' => 'The best'
        ]);

        $log = TeamLog::factory()->create([
            'action' => 'team_created',
            'objects' => json_encode([
                'team_id' => $team->id,
                'team_name' => $team->name,
            ]),
        ]);

        $this->assertIsString(LogHelper::processTeamLog($log));
        $this->assertEquals('Created the team The best.', LogHelper::processTeamLog($log));
        $this->assertNotEquals(trans('account.team_log_team_created'), LogHelper::processTeamLog($log));
    }

    /** @test */
    public function it_returns_empty_by_default_for_audit_log(): void
    {
        $log = AuditLog::factory()->create([
            'action' => '',
        ]);

        $string = LogHelper::processAuditLog($log);

        $this->assertIsString($string);

        $this->assertEquals('', $string);
    }

    /** @test */
    public function it_returns_empty_by_default_for_employee_log(): void
    {
        $log = EmployeeLog::factory()->create([
            'action' => '',
        ]);

        $string = LogHelper::processEmployeeLog($log);

        $this->assertIsString($string);

        $this->assertEquals('', $string);
    }

    /** @test */
    public function it_returns_empty_by_default_for_team_log(): void
    {
        $log = TeamLog::factory()->create([
            'action' => '',
        ]);

        $string = LogHelper::processTeamLog($log);

        $this->assertIsString($string);

        $this->assertEquals('', $string);
    }
}
