<?php

namespace Tests\Unit\Services\Company\Adminland\CompanyPTOPolicy;

use Exception;
use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\TeamNews;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Team\TeamNews\UpdateTeamNews;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateTeamNewsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_company_news_as_administrator(): void
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
    public function it_updates_a_company_news_as_hr(): void
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
    public function it_updates_a_company_news_as_employee(): void
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
    public function it_cant_update_the_team_news_if_the_team_is_not_linked_to_the_company(): void
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
            'title' => 'Assistant to the regional manager',
            'content' => 'Wonderful article',
        ];

        $this->expectException(Exception::class);
        (new UpdateTeamNews)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateTeamNews)->execute($request);
    }

    private function executeService(Employee $michael, Team $team, TeamNews $news): void
    {
        Queue::fake();

        $oldNews = $news->title;

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'team_news_id' => $news->id,
            'title' => 'Assistant to the regional manager',
            'content' => 'Wonderful article',
        ];

        $news = (new UpdateTeamNews)->execute($request);

        $this->assertDatabaseHas('team_news', [
            'id' => $news->id,
            'team_id' => $team->id,
            'author_id' => $michael->id,
            'title' => 'Assistant to the regional manager',
            'content' => 'Wonderful article',
        ]);

        $this->assertInstanceOf(
            TeamNews::class,
            $news
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $news, $oldNews, $team) {
            return $job->auditLog['action'] === 'team_news_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_id' => $team->id,
                    'team_name' => $team->name,
                    'team_news_id' => $news->id,
                    'team_news_title' => $news->title,
                    'team_news_old_title' => $oldNews,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $news, $oldNews) {
            return $job->auditLog['action'] === 'team_news_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_news_id' => $news->id,
                    'team_news_title' => $news->title,
                    'team_news_old_title' => $oldNews,
                ]);
        });
    }
}
