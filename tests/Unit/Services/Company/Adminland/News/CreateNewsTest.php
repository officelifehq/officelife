<?php

namespace Tests\Unit\Services\Company\Adminland\News;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\CompanyNews;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Adminland\News\CreateNews;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateNewsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_company_news() : void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user_id,
            'title' => 'Assistant to the regional manager',
            'content' => 'Wonderful article',
        ];

        $news = (new CreateNews)->execute($request);

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

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $news) {
            return $job->auditLog['action'] === 'company_news_created' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->id,
                    'author_name' => $michael->name,
                    'news_id' => $news->id,
                    'news_title' => $news->title,
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
        (new CreateNews)->execute($request);
    }
}
