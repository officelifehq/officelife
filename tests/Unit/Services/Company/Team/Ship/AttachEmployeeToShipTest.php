<?php

namespace Tests\Unit\Services\Company\Team\Ship;

use Tests\TestCase;
use App\Jobs\NotifyEmployee;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Team\Ship\AttachEmployeeToShip;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AttachEmployeeToShipTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_attaches_an_employee_to_a_recent_ship_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $ship = Ship::factory()->create([
            'team_id' => $team->id,
        ]);

        $this->executeService($michael, $dwight, $ship);
    }

    /** @test */
    public function it_attaches_an_employee_to_a_recent_ship_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $ship = Ship::factory()->create([
            'team_id' => $team->id,
        ]);

        $this->executeService($michael, $dwight, $ship);
    }

    /** @test */
    public function it_attaches_an_employee_to_a_recent_ship_as_normal_user_part_of_the_team(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $ship = Ship::factory()->create([
            'team_id' => $team->id,
        ]);

        $this->executeService($michael, $dwight, $ship);
    }

    /** @test */
    public function it_fails_if_the_team_is_not_part_of_the_company(): void
    {
        $michael = $this->createAdministrator();
        $team = Team::factory()->create([]);
        $ship = Ship::factory()->create([
            'team_id' => $team->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $michael, $ship);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();
        Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
        ];

        $this->expectException(ValidationException::class);
        (new AttachEmployeeToShip)->execute($request);
    }

    private function executeService(Employee $michael, Employee $dwight, Ship $ship): void
    {
        Queue::fake();

        $ship->employees()->attach($michael->id);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'ship_id' => $ship->id,
        ];

        $dwight = (new AttachEmployeeToShip)->execute($request);

        $this->assertDatabaseHas('employee_ship', [
            'employee_id' => $dwight->id,
            'ship_id' => $ship->id,
        ]);

        $this->assertInstanceOf(
            Employee::class,
            $dwight
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight, $ship) {
            return $job->auditLog['action'] === 'employee_attached_to_recent_ship' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'team_id' => $ship->team->id,
                    'team_name' => $ship->team->name,
                    'ship_id' => $ship->id,
                    'ship_title' => $ship->title,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $dwight, $ship) {
            return $job->auditLog['action'] === 'employee_attached_to_recent_ship' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'team_id' => $ship->team->id,
                    'team_name' => $ship->team->name,
                    'ship_id' => $ship->id,
                    'ship_title' => $ship->title,
                ]);
        });

        Queue::assertPushed(NotifyEmployee::class, function ($job) use ($ship, $dwight) {
            return $job->notification['action'] === 'employee_attached_to_recent_ship' &&
                $job->notification['employee_id'] === $dwight->id &&
                $job->notification['objects'] === json_encode([
                    'ship_id' => $ship->id,
                    'ship_title' => $ship->title,
                ]);
        });
    }
}
