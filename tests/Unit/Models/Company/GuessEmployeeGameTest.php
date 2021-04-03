<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\GuessEmployeeGame;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GuessEmployeeGameTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_player(): void
    {
        $game = GuessEmployeeGame::factory()->create([]);
        $this->assertTrue($game->player()->exists());
    }

    /** @test */
    public function it_belongs_to_a_player_to_find(): void
    {
        $game = GuessEmployeeGame::factory()->create([]);
        $this->assertTrue($game->employeeToFind()->exists());
    }

    /** @test */
    public function it_belongs_to_another_player_to_find(): void
    {
        $game = GuessEmployeeGame::factory()->create([]);
        $this->assertTrue($game->firstOtherEmployeeToFind()->exists());
    }

    /** @test */
    public function it_belongs_to_yet_another_player_to_find(): void
    {
        $game = GuessEmployeeGame::factory()->create([]);
        $this->assertTrue($game->secondOtherEmployeeToFind()->exists());
    }
}
