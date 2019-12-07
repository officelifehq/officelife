<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\TeamNews;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamNewsTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_team(): void
    {
        $news = factory(TeamNews::class)->create([]);
        $this->assertTrue($news->team()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $news = factory(TeamNews::class)->create([]);
        $this->assertTrue($news->author()->exists());
    }
}
