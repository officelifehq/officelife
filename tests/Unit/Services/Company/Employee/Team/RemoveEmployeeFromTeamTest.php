<?php

namespace Tests\Unit\Services\Company\Employee\Team;

use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Team\RemoveEmployeeFromTeam;

class RemoveEmployeeFromTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_an_employee_from_a_team() : void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        DB::table('employee_team')->insert([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'team_id' => $team->id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user->id,
            'employee_id' => $michael->id,
            'team_id' => $team->id,
        ];

        $michael = (new RemoveEmployeeFromTeam)->execute($request);

        $this->assertDatabaseMissing('employee_team', [
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'team_id' => $team->id,
        ]);

        $this->assertInstanceOf(
            Team::class,
            $team
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $team) {
            return $job->auditLog['action'] === 'employee_removed_from_team' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'team_id' => $team->id,
                    'team_name' => $team->name,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $team) {
            return $job->auditLog['action'] === 'employee_removed_from_team' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'team_id' => $team->id,
                    'team_name' => $team->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $team) {
            return $job->auditLog['action'] === 'employee_removed_from_team' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'team_id' => $team->id,
                    'team_name' => $team->name,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $michael = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        DB::table('employee_team')->insert([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'team_id' => $team->id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user->id,
            'team_id' => $team->id,
        ];

        $this->expectException(ValidationException::class);
        (new RemoveEmployeeFromTeam)->execute($request);
    }
}
