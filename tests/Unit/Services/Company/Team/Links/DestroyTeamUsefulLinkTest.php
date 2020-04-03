<?php

namespace Tests\Unit\Services\Company\Employee\Team\Links;

use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\TeamUsefulLink;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Team\Links\DestroyTeamUsefulLink;

class DestroyTeamUsefulLinkTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_team_useful_link_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $link = factory(TeamUsefulLink::class)->create([
            'team_id' => $team->id,
        ]);

        $this->executeService($michael, $link);
    }

    /** @test */
    public function it_destroys_a_team_useful_link_as_hr(): void
    {
        $michael = $this->createHR();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $link = factory(TeamUsefulLink::class)->create([
            'team_id' => $team->id,
        ]);

        $this->executeService($michael, $link);
    }

    /** @test */
    public function it_destroys_a_team_useful_link_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $link = factory(TeamUsefulLink::class)->create([
            'team_id' => $team->id,
        ]);

        $this->executeService($michael, $link);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyTeamUsefulLink)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_team_is_not_part_of_the_company(): void
    {
        $michael = $this->createEmployee();
        factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $link = factory(TeamUsefulLink::class)->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $link);
    }

    private function executeService(Employee $michael, TeamUsefulLink $link): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'team_useful_link_id' => $link->id,
        ];

        (new DestroyTeamUsefulLink)->execute($request);

        $this->assertDatabaseMissing('team_useful_links', [
            'id' => $link->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $link) {
            return $job->auditLog['action'] === 'team_useful_link_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'link_name' => $link->label,
                    'team_id' => $link->team->id,
                    'team_name' => $link->team->name,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $link) {
            return $job->auditLog['action'] === 'useful_link_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'link_name' => $link->label,
                ]);
        });
    }
}
