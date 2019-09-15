<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\CompanyNews;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyNewsTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company() : void
    {
        $news = factory(CompanyNews::class)->create([]);
        $this->assertTrue($news->company()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee() : void
    {
        $news = factory(CompanyNews::class)->create([]);
        $this->assertTrue($news->author()->exists());
    }
}
