<?php

namespace App\Services\User;

use App\Models\User\User;
use App\Services\BaseService;
use App\Helpers\TimezoneHelper;
use Illuminate\Validation\Rule;

class UpdateTimezone extends BaseService
{
    protected array $data;
    protected User $user;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'timezone' => [
                'required',
                'string',
                Rule::in(array_map(function ($timezone) {
                    return $timezone['value'];
                }, TimezoneHelper::getListOfTimezones())),
            ],
        ];
    }

    /**
     * Update the user locale.
     *
     * @param array $data
     * @return User
     */
    public function execute(array $data): User
    {
        $this->data = $data;
        $this->validate();
        $this->update();

        return $this->user;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
    }

    private function update(): void
    {
        $this->user->timezone = $this->data['timezone'];
        $this->user->save();
    }
}
