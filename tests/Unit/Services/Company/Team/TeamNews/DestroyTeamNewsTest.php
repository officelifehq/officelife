<?php

namespace Tests\Unit\Services\Company\Adminland\CompanyNews;

use Exception;
use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\TeamNews;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Team\TeamNews\DestroyTeamNews;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DestroyTeamNewsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_company_news_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $news = factory(TeamNews::class)->create([
            'author_id' => $michael->id,
            'team_id' => $team->id,
        ]);

        $this->executeService($michael, $team, $news);
    }

    /** @test */
    public function it_destroys_a_company_news_as_hr(): void
    {
        $michael = $this->createHR();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $news = factory(TeamNews::class)->create([
            'author_id' => $michael->id,
            'team_id' => $team->id,
        ]);

        $this->executeService($michael, $team, $news);
    }

    /** @test */
    public function it_destroys_a_company_news_as_employee(): void
    {
        $michael = $this->createEmployee();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $news = factory(TeamNews::class)->create([
            'author_id' => $michael->id,
            'team_id' => $team->id,
        ]);

        $this->executeService($michael, $team, $news);
    }

    /** @test */
    public function it_cant_destroy_the_team_news_if_the_team_is_not_linked_to_the_company(): void
    {
        $michael = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([]);
        $news = factory(TeamNews::class)->create([
            'author_id' => $michael->id,
            'team_id' => $team->id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'team_news_id' => $news->id,
        ];

        $this->expectException(Exception::class);
        (new DestroyTeamNews)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyTeamNews)->execute($request);
    }

    private function executeService(Employee $michael, Team $team, TeamNews $news): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'team_news_id' => $news->id,
        ];

        (new DestroyTeamNews)->execute($request);

        $this->assertDatabaseMissing('team_news', [
            'id' => $news->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $news, $team) {
            return $job->auditLog['action'] === 'team_news_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_id' => $team->id,
                    'team_name' => $team->name,
                    'team_news_title' => $news->title,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $news) {
            return $job->auditLog['action'] === 'team_news_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_news_title' => $news->title,
                ]);
        });
    }
}
