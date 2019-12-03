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
use App\Services\Company\Team\Links\CreateTeamUsefulLink;

class CreateTeamUsefulLinkTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_team_useful_link(): void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'team_id' => $team->id,
            'type' => 'slack',
            'label' => '#dunder-mifflin',
            'url' => 'https://slack.com',
        ];

        $link = (new CreateTeamUsefulLink)->execute($request);

        $this->assertDatabaseHas('team_useful_links', [
            'id' => $link->id,
            'team_id' => $team->id,
            'type' => 'slack',
            'label' => '#dunder-mifflin',
            'url' => 'https://slack.com',
        ]);

        $this->assertInstanceOf(
            TeamUsefulLink::class,
            $link
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $link, $team) {
            return $job->auditLog['action'] === 'team_useful_link_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'link_id' => $link->id,
                    'link_name' => $link->label,
                    'team_id' => $team->id,
                    'team_name' => $team->name,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'useful_link_created' &&
                $job->auditLog['author_id'] === $michael->id;
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateTeamUsefulLink)->execute($request);
    }
}
