<?php

namespace Tests\Unit\Jobs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\Dummy\CreateDummyTeam;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateDummyTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_team(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();

        CreateDummyTeam::dispatch([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'Sales',
        ]);

        $this->assertDatabaseHas('teams', [
            'company_id' => $michael->company_id,
            'name' => 'Sales',
            'is_dummy' => true,
        ]);
    }
}
