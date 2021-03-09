<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\Company\CompanyNews;
use App\Http\Collections\CompanyNewsCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyNewsCollectionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_a_collection(): void
    {
        $michael = $this->createAdministrator();
        CompanyNews::factory()->count(2)->create([
            'company_id' => $michael->company_id,
        ]);

        $news = $michael->company->news()->with('author')->orderBy('created_at', 'desc')->get();
        $collection = CompanyNewsCollection::prepare($news);

        $this->assertEquals(
            2,
            $collection->count()
        );
    }
}
