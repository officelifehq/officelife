<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\Company\Worklog;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WorklogTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $worklog = factory(Worklog::class)->create([]);
        $this->assertTrue($worklog->employee()->exists());
    }
}
