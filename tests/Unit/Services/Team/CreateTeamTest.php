<?php

namespace Tests\Unit\Services\Account\Team;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\Team;
use App\Models\Company\Company;
use App\Services\Company\Team\CreateTeam;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Company\Employee;

class CreateTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_team()
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'name' => 'Selling team',
        ];

        $team = (new CreateTeam)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'company_id' => $employee->company_id,
            'name' => 'Selling team',
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

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'name' => 'Selling team',
            'description' => 'Selling paper everyday',
        ];

        $team = (new CreateTeam)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'team_created',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new CreateTeam)->execute($request);
    }
}
