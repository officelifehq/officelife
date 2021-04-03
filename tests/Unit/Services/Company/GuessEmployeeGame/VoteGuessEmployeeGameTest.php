<?php

namespace Tests\Unit\Services\Company\GuessEmployeeGame;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\GuessEmployeeGame;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\GuessEmployeeGame\VoteGuessEmployeeGame;

class VoteGuessEmployeeGameTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_votes_a_game_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $game = $this->populateAccount($michael);
        $this->executeService($michael, $game);
    }

    /** @test */
    public function it_votes_a_game_as_hr(): void
    {
        $michael = $this->createHR();
        $game = $this->populateAccount($michael);
        $this->executeService($michael, $game);
    }

    /** @test */
    public function it_votes_a_game_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $game = $this->populateAccount($michael);
        $this->executeService($michael, $game);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new VoteGuessEmployeeGame)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_game_is_not_linked_to_the_employee(): void
    {
        $michael = Employee::factory()->create([]);
        $dwight = Employee::factory()->create([]);
        $game = $this->populateAccount($dwight);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'game_id' => $game->id,
            'choice_id' => $michael->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new VoteGuessEmployeeGame)->execute($request);
    }

    private function populateAccount(Employee $michael): GuessEmployeeGame
    {
        $dwight = $this->createAnotherEmployee($michael);
        $jim = $this->createAnotherEmployee($michael);
        $pam = $this->createAnotherEmployee($michael);

        return GuessEmployeeGame::create([
            'employee_who_played_id' => $michael->id,
            'employee_to_find_id' => $dwight->id,
            'first_other_employee_to_find_id' => $jim->id,
            'second_other_employee_to_find_id' => $pam->id,
            'played' => false,
            'found' => false,
        ]);
    }

    private function executeService(Employee $michael, GuessEmployeeGame $game): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'game_id' => $game->id,
            'choice_id' => $michael->id,
        ];

        $game = (new VoteGuessEmployeeGame)->execute($request);

        $this->assertDatabaseHas('guess_employee_games', [
            'id' => $game->id,
            'employee_who_played_id' => $michael->id,
            'played' => true,
            'found' => false,
        ]);

        $this->assertInstanceOf(
            GuessEmployeeGame::class,
            $game
        );
    }
}
