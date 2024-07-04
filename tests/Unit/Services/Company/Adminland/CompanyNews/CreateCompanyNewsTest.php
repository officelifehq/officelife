<?php

namespace Tests\Unit\Services\Company\Adminland\CompanyNews;

use App\Exceptions\NotEnoughPermissionException;
use App\Jobs\LogAccountAudit;
use App\Models\Company\CompanyNews;
use App\Models\Company\Employee;
use App\Services\Company\Adminland\CompanyNews\CreateCompanyNews;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateCompanyNewsTest extends TestCase
{
    /** @test */
    public function it_creates_a_company_news_as_administrator(): void
    {
        Queue::fake();

        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_company_news_as_hr(): void
    {
        Queue::fake();

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
        (new CreateCompanyNews)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'title' => 'Assistant to the regional manager',
            'content' => 'Wonderful article',
        ];

        $news = (new CreateCompanyNews)->execute($request);

        $this->assertDatabaseHas('company_news', [
            'id' => $news->id,
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'title' => 'Assistant to the regional manager',
            'content' => 'Wonderful article',
        ]);

        $this->assertInstanceOf(
            CompanyNews::class,
            $news
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $news) {
            return $job->auditLog['action'] === 'company_news_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'company_news_id' => $news->id,
                    'company_news_title' => $news->title,
                ]);
        });
    }
}
