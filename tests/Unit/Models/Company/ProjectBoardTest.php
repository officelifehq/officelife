<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ProjectBoard;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectBoardTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $board = ProjectBoard::factory()->create();
        $this->assertTrue($board->project()->exists());
    }
}
