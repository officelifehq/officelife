<?php

namespace Tests\Unit\Services\Company\Employee\Team;

use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Team\AddEmployeeToTeam;

class AddEmployeeToTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_an_employee_to_a_team() : void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'team_id' => $team->id,
        ];

        $michael = (new AddEmployeeToTeam)->execute($request);

        $this->assertDatabaseHas('employee_team', [
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'team_id' => $team->id,
        ]);

        $this->assertInstanceOf(
            Team::class,
            $team
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $team) {
            return $job->auditLog['action'] === 'employee_added_to_team' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'team_id' => $team->id,
                    'team_name' => $team->name,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $team) {
            return $job->auditLog['action'] === 'employee_added_to_team' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'team_id' => $team->id,
                    'team_name' => $team->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $team) {
            return $job->auditLog['action'] === 'employee_added_to_team' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
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

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'team_id' => $team->id,
        ];

        $this->expectException(ValidationException::class);
        (new AddEmployeeToTeam)->execute($request);
    }
}
