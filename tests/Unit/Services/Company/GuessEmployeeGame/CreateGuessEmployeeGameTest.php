<?php

namespace Tests\Unit\Services\Company\GuessEmployeeGame;

use Tests\TestCase;
use OutOfRangeException;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\GuessEmployeeGame;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\GuessEmployeeGame\CreateGuessEmployeeGame;

class CreateGuessEmployeeGameTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_game_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $michael->pronoun_id = 1;
        $michael->save();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_game_as_hr(): void
    {
        $michael = $this->createHR();
        $michael->pronoun_id = 1;
        $michael->save();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_game_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $michael->pronoun_id = 1;
        $michael->save();
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateGuessEmployeeGame)->execute($request);
    }

    /** @test */
    public function it_throws_an_exception_if_there_are_less_than_3_employees_in_the_company(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
        ];

        $this->expectException(OutOfRangeException::class);
        (new CreateGuessEmployeeGame)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $dwight = $this->createAnotherEmployee($michael);
        $dwight->pronoun_id = 1;
        $dwight->save();
        $jim = $this->createAnotherEmployee($michael);
        $jim->pronoun_id = 1;
        $jim->save();
        $pam = $this->createAnotherEmployee($michael);
        $pam->pronoun_id = 1;
        $pam->save();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
        ];

        $game = (new CreateGuessEmployeeGame)->execute($request);

        $this->assertDatabaseHas('guess_employee_games', [
            'id' => $game->id,
            'employee_who_played_id' => $michael->id,
            'played' => false,
            'found' => false,
        ]);

        $this->assertInstanceOf(
            GuessEmployeeGame::class,
            $game
        );
    }
}
