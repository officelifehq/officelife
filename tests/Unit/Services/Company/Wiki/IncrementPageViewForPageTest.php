<?php

namespace Tests\Unit\Services\Company\Wiki;

use Tests\TestCase;
use App\Models\Company\Page;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Wiki\IncrementPageViewForPage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IncrementPageViewForPageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_increments_the_page_view_on_a_page_as_admin(): void
    {
        $michael = $this->createAdministrator();
        $page = Page::factory()->create();
        $this->executeService($michael, $page);
    }

    /** @test */
    public function it_increments_the_page_view_on_a_page_as_hr(): void
    {
        $michael = $this->createHR();
        $page = Page::factory()->create();
        $this->executeService($michael, $page);
    }

    /** @test */
    public function it_increments_the_page_view_on_a_page_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $page = Page::factory()->create();
        $this->executeService($michael, $page);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new IncrementPageViewForPage)->execute($request);
    }

    private function executeService(Employee $michael, Page $page): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'page_id' => $page->id,
        ];

        (new IncrementPageViewForPage)->execute($request);

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'pageviews_counter' => 1,
        ]);

        $this->assertDatabaseHas('pageviews', [
            'page_id' => $page->id,
            'employee_id' => $michael->id,
            'employee_name' => $michael->name,
        ]);
    }
}
