<?php

namespace Tests\Unit\Services\Company\Adminland\CompanyNews;

use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\TeamNews;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Team\TeamNews\CreateTeamNews;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateTeamNewsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_team_news(): void
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
            'title' => 'Assistant to the regional manager',
            'content' => 'Wonderful article',
        ];

        $news = (new CreateTeamNews)->execute($request);

        $this->assertDatabaseHas('team_news', [
            'id' => $news->id,
            'team_id' => $team->id,
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'title' => 'Assistant to the regional manager',
            'content' => 'Wonderful article',
        ]);

        $this->assertInstanceOf(
            TeamNews::class,
            $news
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $news, $team) {
            return $job->auditLog['action'] === 'team_news_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_id' => $team->id,
                    'team_name' => $team->id,
                    'team_news_id' => $news->id,
                    'team_news_title' => $news->title,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $news) {
            return $job->auditLog['action'] === 'team_news_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'team_news_id' => $news->id,
                    'team_news_title' => $news->title,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new CreateTeamNews)->execute($request);
    }
}
