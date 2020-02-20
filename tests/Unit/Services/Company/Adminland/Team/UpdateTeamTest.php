<?php

namespace Tests\Unit\Services\Company\Adminland\Team;

use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Exceptions\TeamNameNotUniqueException;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Adminland\Team\UpdateTeam;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_team(): void
    {
        Queue::fake();

        $sales = factory(Team::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $sales->company_id,
        ]);

        $request = [
            'company_id' => $sales->company_id,
            'author_id' => $michael->id,
            'team_id' => $sales->id,
            'name' => 'Selling team',
        ];

        (new UpdateTeam)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $sales->id,
            'company_id' => $sales->company_id,
            'name' => 'Selling team',
        ]);

        $this->assertInstanceOf(
            Team::class,
            $sales
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $sales) {
            return $job->auditLog['action'] === 'team_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_id' => $sales->id,
                    'team_old_name' => $sales->name,
                    'team_new_name' => 'Selling team',
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $sales) {
            return $job->auditLog['action'] === 'team_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_old_name' => $sales->name,
                    'team_new_name' => 'Selling team',
                ]);
        });
    }

    /** @test */
    public function it_cant_update_a_team_with_a_not_unique_name(): void
    {
        $michael = factory(Employee::class)->create([]);
        $sales = factory(Team::class)->create([
            'company_id' => $michael->company_id,
            'name' => 'Sales Team',
        ]);

        $product = factory(Team::class)->create([
            'company_id' => $michael->company_id,
            'name' => 'Product Team',
        ]);

        $request = [
            'company_id' => $sales->company_id,
            'author_id' => $michael->id,
            'team_id' => $sales->id,
            'name' => 'product team',
        ];

        $this->expectException(TeamNameNotUniqueException::class);
        (new UpdateTeam)->execute($request);
    }

    /** @test */
    public function it_can_update_a_team_with_a_name_already_taken_by_a_team_in_another_company(): void
    {
        $michael = factory(Employee::class)->create([]);
        $sales = factory(Team::class)->create([
            'company_id' => $michael->company_id,
            'name' => 'Sales Team',
        ]);

        factory(Team::class)->create([
            'name' => 'Product Team',
        ]);

        $request = [
            'company_id' => $sales->company_id,
            'author_id' => $michael->id,
            'team_id' => $sales->id,
            'name' => 'product team',
        ];

        $sales = (new UpdateTeam)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $sales->id,
            'company_id' => $michael->company_id,
            'name' => 'product team',
        ]);

        $this->assertInstanceOf(
            Team::class,
            $sales
        );
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateTeam)->execute($request);
    }
}
