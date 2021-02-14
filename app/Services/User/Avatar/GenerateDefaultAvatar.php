<?php

namespace App\Services\User\Avatar;

use App\Services\BaseService;

class GenerateDefaultAvatar extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    /**
     * Get an url for an avatar.
     *
     * @param array $data
     *
     * @return string|null
     */
    public function execute(array $data)
    {
        $this->validateRules($data);

        return 'https://ui-avatars.com/api/?name='.$data['name'];
    }
}
