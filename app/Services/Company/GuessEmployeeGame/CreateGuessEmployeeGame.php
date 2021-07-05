<?php

namespace App\Services\Company\GuessEmployeeGame;

use OutOfRangeException;
use App\Services\BaseService;
use App\Models\Company\Answer;
use App\Models\Company\Employee;
use App\Models\Company\GuessEmployeeGame;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateGuessEmployeeGame extends BaseService
{
    protected array $data;
    protected GuessEmployeeGame $game;
    protected Employee $employee;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|integer|exists:employees,id',
        ];
    }

    /**
     * Create a Guess Employee Game.
     * In this game, an employee must guess what's the name of another employee,
     * based on his/her avatar.
     * Rules of this game:
     *  - the game shows 3 names
     *  - the player has to find the name of the employee
     *  - the players to find should all have the same gender
     *  - whatever the answer, another game loads once it's played.
     *
     * @param array $data
     * @return GuessEmployeeGame
     */
    public function execute(array $data): GuessEmployeeGame
    {
        $this->data = $data;
        $this->checkPermission();

        if (! $this->checkIfPreviousGameExistsButWasntPlayedYet()) {
            $this->createNewGame();
        }

        return $this->game;
    }

    private function checkPermission(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($this->data);
    }

    private function checkIfPreviousGameExistsButWasntPlayedYet(): bool
    {
        try {
            $this->game = GuessEmployeeGame::where('employee_who_played_id', $this->data['employee_id'])
            ->where('played', false)
            ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return true;
    }

    private function createNewGame(): void
    {
        $employeeToFind = $this->employee->company->employees()
            ->inRandomOrder()
            ->first();

        $twoOtherEmployees = $this->employee->company->employees()
            ->notLocked()
            ->where('id', '!=', $employeeToFind->id)
            ->where('id', '!=', $this->employee->id)
            ->where('pronoun_id', $employeeToFind->pronoun_id)
            ->select('id', 'first_name', 'last_name')
            ->inRandomOrder()
            ->take(2)
            ->get();

        if ($twoOtherEmployees->count() != 2) {
            throw new OutOfRangeException();
        }

        $this->game = GuessEmployeeGame::create([
            'employee_who_played_id' => $this->employee->id,
            'employee_to_find_id' => $employeeToFind->id,
            'first_other_employee_to_find_id' => $twoOtherEmployees->get(0)->id,
            'second_other_employee_to_find_id' => $twoOtherEmployees->get(1)->id,
            'played' => false,
            'found' => false,
        ]);
    }
}
