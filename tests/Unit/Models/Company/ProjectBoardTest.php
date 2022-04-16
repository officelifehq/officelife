<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ProjectBoard;
use App\Models\Company\ProjectSprint;
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

    /** @test */
    public function it_has_many_sprints(): void
    {
        $board = ProjectBoard::factory()->create();
        ProjectSprint::factory()->create([
            'is_board_backlog' => true,
            'project_board_id' => $board->id,
        ]);
        ProjectSprint::factory()->create([
            'is_board_backlog' => false,
            'project_board_id' => $board->id,
        ]);

        $this->assertTrue($board->sprints()->exists());
        $this->assertEquals(1, $board->sprints->count());
    }

    /** @test */
    public function it_has_one_backlog(): void
    {
        $board = ProjectBoard::factory()->create();
        $backlog = ProjectSprint::factory()->create([
            'is_board_backlog' => true,
            'project_board_id' => $board->id,
        ]);
        ProjectSprint::factory()->create([
            'is_board_backlog' => false,
            'project_board_id' => $board->id,
        ]);

        $this->assertTrue($board->backlog()->exists());

        $this->assertEquals($backlog->id, $board->backlog->id);
    }
}
