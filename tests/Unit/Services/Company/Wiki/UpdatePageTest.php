<?php

namespace Tests\Unit\Services\Company\Wiki;

use Tests\TestCase;
use App\Models\Company\Page;
use App\Models\Company\Wiki;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Services\Company\Wiki\UpdatePage;
use App\Services\Company\Wiki\AddPageToWiki;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdatePageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_edits_a_page_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $wiki = Wiki::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $page = Page::factory()->create([
            'wiki_id' => $wiki->id,
        ]);
        $this->executeService($michael, $wiki, $page);
    }

    /** @test */
    public function it_edits_a_page_as_hr(): void
    {
        $michael = $this->createHR();
        $wiki = Wiki::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $page = Page::factory()->create([
            'wiki_id' => $wiki->id,
        ]);
        $this->executeService($michael, $wiki, $page);
    }

    /** @test */
    public function it_edits_a_page_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $wiki = Wiki::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $page = Page::factory()->create([
            'wiki_id' => $wiki->id,
        ]);
        $this->executeService($michael, $wiki, $page);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new AddPageToWiki)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_wiki_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $wiki = Wiki::factory()->create([]);
        $page = Page::factory()->create([
            'wiki_id' => $wiki->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $wiki, $page);
    }

    /** @test */
    public function it_fails_if_the_page_is_not_in_the_wiki(): void
    {
        $michael = $this->createAdministrator();
        $wiki = Wiki::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $page = Page::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $wiki, $page);
    }

    private function executeService(Employee $michael, Wiki $wiki, Page $page): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'wiki_id' => $wiki->id,
            'page_id' => $page->id,
            'title' => 'title',
            'content' => 'content',
        ];

        $page = (new UpdatePage)->execute($request);

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'wiki_id' => $page->wiki_id,
            'title' => $page->title,
            'content' => $page->content,
        ]);

        $this->assertDatabaseHas('page_revisions', [
            'page_id' => $page->id,
            'employee_id' => $michael->id,
            'employee_name' => $michael->name,
            'title' => $page->title,
            'content' => $page->content,
        ]);

        $this->assertInstanceOf(
            Page::class,
            $page
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $wiki, $page) {
            return $job->auditLog['action'] === 'page_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'wiki_id' => $wiki->id,
                    'wiki_title' => $wiki->title,
                    'page_id' => $page->id,
                    'page_title' => $page->title,
                ]);
        });
    }
}
