<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Action;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActionTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_step(): void
    {
        $action = Action::factory()->create([]);
        $this->assertTrue($action->step()->exists());
    }
}
