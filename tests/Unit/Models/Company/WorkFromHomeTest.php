<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\WorkFromHome;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WorkFromHomeTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $workFromHome = factory(WorkFromHome::class)->create([]);
        $this->assertTrue($workFromHome->employee()->exists());
    }
}
