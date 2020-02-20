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
use App\Services\Company\Adminland\Team\CreateTeam;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateTeamTest extends TestCase
{
    use DatabaseTransactions;


    public function it_creates_a_team_with_a_unique_name(): void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'Selling team',
        ];

        $sales = (new CreateTeam)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $sales->id,
            'company_id' => $michael->company_id,
            'name' => 'Selling team',
        ]);

        $this->assertInstanceOf(
            Team::class,
            $sales
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $sales) {
            return $job->auditLog['action'] === 'team_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_id' => $sales->id,
                    'team_name' => $sales->name,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $sales) {
            return $job->auditLog['action'] === 'team_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_id' => $sales->id,
                    'team_name' => $sales->name,
                ]);
        });
    }

    /** @test */
    public function it_cant_create_a_team_with_a_not_unique_name(): void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        factory(Team::class)->create([
            'company_id' => $michael->company_id,
            'name' => 'Product',
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'Product',
        ];

        $this->expectException(TeamNameNotUniqueException::class);
        (new CreateTeam)->execute($request);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'product    ',
        ];

        $this->expectException(TeamNameNotUniqueException::class);
        (new CreateTeam)->execute($request);
    }

    /** @test */
    public function it_can_create_a_team_with_a_name_already_taken_by_a_team_in_another_company(): void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        factory(Team::class)->create([
            'name' => 'Product',
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'Product',
        ];

        $sales = (new CreateTeam)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $sales->id,
            'company_id' => $michael->company_id,
            'name' => 'Product',
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
        (new CreateTeam)->execute($request);
    }
}
