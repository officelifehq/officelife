<?php

namespace Tests\Unit\Services\Company\Employee\Team;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Team\RemoveEmployeeFromTeam;

class RemoveEmployeeFromTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_an_employee_from_a_team()
    {
        $employee = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        DB::table('employee_team')->insert([
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'team_id' => $team->id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user->id,
            'employee_id' => $employee->id,
            'team_id' => $team->id,
        ];

        $employee = (new RemoveEmployeeFromTeam)->execute($request);

        $this->assertDatabaseMissing('employee_team', [
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'team_id' => $team->id,
        ]);

        $this->assertInstanceOf(
            Team::class,
            $team
        );
    }

    /** @test */
    public function it_logs_an_action()
    {
        $employee = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        DB::table('employee_team')->insert([
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'team_id' => $team->id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user->id,
            'employee_id' => $employee->id,
            'team_id' => $team->id,
        ];

        $employee = (new RemoveEmployeeFromTeam)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'employee_removed_from_team',
        ]);

        $this->assertDatabaseHas('team_logs', [
            'company_id' => $employee->company_id,
            'team_id' => $team->id,
            'action' => 'employee_removed_from_team',
        ]);

        $this->assertDatabaseHas('employee_logs', [
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'action' => 'employee_removed_from_team',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $employee = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        DB::table('employee_team')->insert([
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'team_id' => $team->id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user->id,
            'team_id' => $team->id,
        ];

        $this->expectException(ValidationException::class);
        (new RemoveEmployeeFromTeam)->execute($request);
    }
}
