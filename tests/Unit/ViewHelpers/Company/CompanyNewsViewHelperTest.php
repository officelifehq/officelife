<?php

namespace Tests\Unit\ViewHelpers\Company;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\CompanyNews;
use GrahamCampbell\TestBenchCore\HelperTrait;
use App\Http\ViewHelpers\Company\CompanyNewsViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyNewsViewHelperTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_the_company_news_in_the_company(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $news = CompanyNews::factory()->create([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ]);
        $newsWithoutAuthor = CompanyNews::factory()->create([
            'company_id' => $michael->company_id,
            'author_id' => null,
            'author_name' => 'John Legend',
        ]);

        $collection = CompanyNewsViewHelper::index($michael->company);

        $this->assertEquals(
            [
                0 => [
                    'id' => $newsWithoutAuthor->id,
                    'title' => $newsWithoutAuthor->title,
                    'content' => $newsWithoutAuthor->content,
                    'author' => 'John Legend',
                    'written_at' => 'Jan 01, 2018',
                ],
                1 => [
                    'id' => $news->id,
                    'title' => $news->title,
                    'content' => $news->content,
                    'author' => [
                        'id' => $michael->id,
                        'name' => $michael->name,
                        'avatar' => ImageHelper::getAvatar($michael, 22),
                        'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                    ],
                    'written_at' => 'Jan 01, 2018',
                ],
            ],
            $collection->toArray()
        );
    }
}
