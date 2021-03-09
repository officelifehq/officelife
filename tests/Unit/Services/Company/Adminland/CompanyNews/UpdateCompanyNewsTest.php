<?php

namespace Tests\Unit\Services\Company\Adminland\CompanyNews;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\CompanyNews;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\CompanyNews\UpdateCompanyNews;

class UpdateCompanyNewsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_company_news_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_updates_a_company_news_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateCompanyNews)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_company_news_does_not_match_the_company(): void
    {
        $michael = $this->createAdministrator();
        $news = CompanyNews::factory()->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'company_news_id' => $news->id,
            'title' => 'Assistant to the regional manager',
            'content' => 'Wonderful article',
        ];

        $this->expectException(ModelNotFoundException::class);
        (new UpdateCompanyNews)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $news = CompanyNews::factory()->create([
            'author_id' => $michael->id,
            'company_id' => $michael->company_id,
        ]);

        $oldNews = $news->title;

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'company_news_id' => $news->id,
            'title' => 'Assistant to the regional manager',
            'content' => 'Wonderful article',
        ];

        $news = (new UpdateCompanyNews)->execute($request);

        $this->assertDatabaseHas('company_news', [
            'id' => $news->id,
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'title' => 'Assistant to the regional manager',
            'content' => 'Wonderful article',
        ]);

        $this->assertInstanceOf(
            CompanyNews::class,
            $news
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $news, $oldNews) {
            return $job->auditLog['action'] === 'company_news_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'company_news_id' => $news->id,
                    'company_news_title' => $news->title,
                    'company_news_old_title' => $oldNews,
                ]);
        });
    }
}
