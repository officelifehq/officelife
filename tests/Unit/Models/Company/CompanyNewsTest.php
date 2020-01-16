<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Employee;
use App\Models\Company\CompanyNews;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyNewsTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $news = factory(CompanyNews::class)->create([]);
        $this->assertTrue($news->company()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $news = factory(CompanyNews::class)->create([]);
        $this->assertTrue($news->author()->exists());
    }

    /** @test */
    public function it_returns_an_object(): void
    {
        $michael = factory(Employee::class)->create([
            'first_name' => 'michael',
            'last_name' => 'scott',
        ]);
        $news = factory(CompanyNews::class)->create([
            'author_id' => $michael->id,
            'author_name' => 'michael scott',
            'title' => 'news',
            'content' => 'a content',
            'created_at' => '2020-01-12 00:00:00',
        ]);

        $this->assertEquals(
            [
                'id' => $news->id,
                'company' => [
                    'id' => $news->company_id,
                ],
                'title' => 'news',
                'content' => 'a content',
                'parsed_content' => '<p>a content</p>',
                'author' => [
                    'id' => $michael->id,
                    'name' => 'michael scott',
                ],
                'localized_created_at' => 'Jan 12, 2020 00:00',
                'created_at' => '2020-01-12 00:00:00',
            ],
            $news->toObject()
        );

        $news = factory(CompanyNews::class)->create([
            'author_id' => null,
            'author_name' => 'michael scott',
            'title' => 'news',
            'content' => 'a content',
            'created_at' => '2020-01-12 00:00:00',
        ]);

        $this->assertEquals(
            [
                'id' => $news->id,
                'company' => [
                    'id' => $news->company_id,
                ],
                'title' => 'news',
                'content' => 'a content',
                'parsed_content' => '<p>a content</p>',
                'author' => [
                    'id' => null,
                    'name' => 'michael scott',
                ],
                'localized_created_at' => 'Jan 12, 2020 00:00',
                'created_at' => '2020-01-12 00:00:00',
            ],
            $news->toObject()
        );
    }
}
