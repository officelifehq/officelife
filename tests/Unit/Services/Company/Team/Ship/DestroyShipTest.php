<?php

namespace Tests\Unit\Services\Company\Team\Ship;

use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Team\Ship\DestroyShip;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyShipTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_recent_ship_entry_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $ship = Ship::factory()->create([
            'team_id' => $team->id,
        ]);

        $this->executeService($michael, $ship);
    }

    /** @test */
    public function it_destroys_a_recent_ship_entry_as_hr(): void
    {
        $michael = $this->createHR();
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $ship = Ship::factory()->create([
            'team_id' => $team->id,
        ]);

        $this->executeService($michael, $ship);
    }

    /** @test */
    public function normal_user_cant_destroy_a_recent_ship_entry(): void
    {
        $michael = $this->createEmployee();
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $ship = Ship::factory()->create([
            'team_id' => $team->id,
        ]);

        $this->executeService($michael, $ship);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyShip)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_ship_entry_is_not_part_of_the_company(): void
    {
        $michael = $this->createHR();
        $ship = Ship::factory()->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $ship);
    }

    private function executeService(Employee $michael, Ship $ship): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'ship_id' => $ship->id,
        ];

        (new DestroyShip)->execute($request);

        $this->assertDatabaseMissing('ships', [
            'id' => $ship->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $ship) {
            return $job->auditLog['action'] === 'ship_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'ship_title' => $ship->title,
                    'team_id' => $ship->team->id,
                    'team_name' => $ship->team->name,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $ship) {
            return $job->auditLog['action'] === 'ship_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'ship_title' => $ship->title,
                ]);
        });
    }
}
