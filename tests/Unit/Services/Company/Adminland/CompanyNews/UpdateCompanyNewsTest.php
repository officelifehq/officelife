<?php

namespace Tests\Unit\Services\Company\Adminland\CompanyPTOPolicy;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\CompanyNews;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\CompanyNews\UpdateCompanyNews;

class UpdateCompanyNewsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_company_news() : void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        $news = factory(CompanyNews::class)->create([
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

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateCompanyNews)->execute($request);
    }
}
