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
use App\Services\Company\Adminland\CompanyNews\DestroyCompanyNews;

class DestroyCompanyNewsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_company_news_as_administrator(): void
    {
        $news = factory(CompanyNews::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $news->company_id,
            'permission_level' => config('officelife.permission_level.administrator'),
        ]);
        $this->executeService($michael, $news);
    }

    /** @test */
    public function it_destroys_a_company_news_as_hr(): void
    {
        $news = factory(CompanyNews::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $news->company_id,
            'permission_level' => config('officelife.permission_level.hr'),
        ]);
        $this->executeService($michael, $news);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $news = factory(CompanyNews::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $news->company_id,
            'permission_level' => config('officelife.permission_level.user'),
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $news);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyCompanyNews)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_company_news_does_not_match_the_company(): void
    {
        $michael = $this->createAdministrator();
        $news = factory(CompanyNews::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'company_news_id' => $news->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new DestroyCompanyNews)->execute($request);
    }

    private function executeService(Employee $michael, CompanyNews $news): void
    {
        Queue::fake();

        $request = [
            'company_id' => $news->company_id,
            'author_id' => $michael->id,
            'company_news_id' => $news->id,
        ];

        (new DestroyCompanyNews)->execute($request);

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
}
