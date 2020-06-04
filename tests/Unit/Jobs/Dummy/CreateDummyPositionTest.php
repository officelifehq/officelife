<?php

namespace Tests\Unit\Jobs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\Dummy\CreateDummyPosition;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateDummyPositionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_position(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();

        CreateDummyPosition::dispatch([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'title' => 'CEO',
        ]);

        $this->assertDatabaseHas('positions', [
            'company_id' => $michael->company_id,
            'title' => 'CEO',
            'is_dummy' => true,
        ]);
    }
}
