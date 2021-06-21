<?php

namespace App\Services\Company\GuessEmployeeGame;

use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\GuessEmployeeGame;

class VoteGuessEmployeeGame extends BaseService
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
            'game_id' => 'required|integer|exists:guess_employee_games,id',
            'choice_id' => 'required|integer|exists:employees,id',
        ];
    }

    /**
     * Vote a Guess Employee Game.
     *
     * @param array $data
     * @return GuessEmployeeGame
     */
    public function execute(array $data): GuessEmployeeGame
    {
        $this->data = $data;
        $this->checkPermission();
        $this->vote();

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

        $this->game = GuessEmployeeGame::where('employee_who_played_id', $this->data['employee_id'])
            ->findOrFail($this->data['game_id']);
    }

    private function vote(): void
    {
        $found = false;
        if ($this->game->employee_to_find_id === $this->data['choice_id']) {
            $found = true;
        }

        $this->game->played = true;
        $this->game->found = $found;
        $this->game->save();
    }
}
