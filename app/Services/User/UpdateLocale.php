<?php

namespace App\Services\User;

use App\Models\User\User;
use App\Services\BaseService;
use Illuminate\Validation\Rule;

class UpdateLocale extends BaseService
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
            'locale' => [
                'required',
                'string',
                Rule::in(config('lang-detector.languages')),
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
        $this->user->locale = $this->data['locale'];
        $this->user->save();
    }
}
