<?php

namespace Tests\Unit\Services\Company\Team\Ship;

use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Jobs\AttachEmployeeToShip;
use Illuminate\Support\Facades\Queue;
use App\Services\Company\Team\Ship\CreateShip;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateShipTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_recent_ship_entry_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $this->executeService($michael, $team);
    }

    /** @test */
    public function it_creates_a_recent_ship_entry_as_administrator_and_associate_employees(): void
    {
        $michael = $this->createAdministrator();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $andrew = $this->createAnotherEmployee($michael);
        $john = $this->createAnotherEmployee($michael);

        $employees = [$andrew->id, $john->id];

        $this->executeService($michael, $team, $employees);
    }

    /** @test */
    public function it_creates_a_recent_ship_entry_as_hr(): void
    {
        $michael = $this->createHR();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $this->executeService($michael, $team);
    }

    /** @test */
    public function it_attaches_an_employee_to_a_recent_ship_as_normal_user_part_of_the_team(): void
    {
        $michael = $this->createEmployee();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $this->executeService($michael, $team);
    }

    /** @test */
    public function it_fails_if_the_team_is_not_part_of_the_company(): void
    {
        $michael = $this->createAdministrator();
        $team = factory(Team::class)->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $team);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = factory(Employee::class)->create([]);
        factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateShip)->execute($request);
    }

    private function executeService(Employee $michael, Team $team, array $employees = null): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'team_id' => $team->id,
            'title' => 'New version of the API',
            'employees' => $employees,
        ];

        $ship = (new CreateShip)->execute($request);

        $this->assertDatabaseHas('ships', [
            'id' => $ship->id,
            'title' => 'New version of the API',
        ]);

        $this->assertInstanceOf(
            Ship::class,
            $ship
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $team, $ship) {
            return $job->auditLog['action'] === 'recent_ship_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_id' => $team->id,
                    'team_name' => $team->name,
                    'ship_id' => $ship->id,
                    'ship_title' => $ship->title,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $ship) {
            return $job->auditLog['action'] === 'recent_ship_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'ship_id' => $ship->id,
                    'ship_title' => $ship->title,
                ]);
        });

        if ($employees) {
            Queue::assertPushed(AttachEmployeeToShip::class, 2);
        }
    }
}
