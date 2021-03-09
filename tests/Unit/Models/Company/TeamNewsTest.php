<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Employee;
use App\Models\Company\TeamNews;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamNewsTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_team(): void
    {
        $news = TeamNews::factory()->create([]);
        $this->assertTrue($news->team()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $news = TeamNews::factory()->create([]);
        $this->assertTrue($news->author()->exists());
    }

    /** @test */
    public function it_returns_an_object(): void
    {
        $michael = Employee::factory()->create([
            'first_name' => 'michael',
            'last_name' => 'scott',
        ]);
        $news = TeamNews::factory()->create([
            'author_id' => $michael->id,
            'author_name' => 'michael scott',
            'title' => 'news',
            'content' => 'a content',
            'created_at' => '2020-01-12 00:00:00',
        ]);

        $this->assertEquals(
            [
                'id' => $news->id,
                'title' => 'news',
                'content' => 'a content',
                'parsed_content' => '<p>a content</p>',
                'author' => [
                    'id' => $michael->id,
                    'name' => 'michael scott',
                    'avatar' => 'https://api.adorable.io/avatars/285/abott@adorable.png',
                ],
                'localized_created_at' => 'Jan 12, 2020 00:00',
                'created_at' => '2020-01-12 00:00:00',
            ],
            $news->toObject()
        );
    }
}
