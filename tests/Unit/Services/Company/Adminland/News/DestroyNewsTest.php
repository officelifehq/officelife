<?php

namespace Tests\Unit\Services\Company\Adminland\News;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\CompanyNews;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Adminland\News\DestroyNews;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DestroyNewsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_company_news(): void
    {
        Queue::fake();

        $news = factory(CompanyNews::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $news->company_id,
        ]);

        $request = [
            'company_id' => $news->company_id,
            'author_id' => $michael->id,
            'company_news_id' => $news->id,
        ];

        (new DestroyNews)->execute($request);

        $this->assertDatabaseMissing('company_news', [
            'id' => $news->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $news) {
            return $job->auditLog['action'] === 'company_news_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'company_news_title' => $news->title,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyNews)->execute($request);
    }
}
