<?php

namespace App\Services\User\Avatar;

use App\Services\BaseService;

class GenerateAvatar extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'uuid' => 'required|string',
            'size' => 'nullable|integer|between:1,2000',
        ];
    }

    /**
     * Get an url for an adorable avatar.
     * - http://avatars.adorable.io/ gives avatars based on a random string.
     *
     * @param array $data
     * @return string|null
     */
    public function execute(array $data)
    {
        $this->validateRules($data);

        $size = $this->size($data);

        return 'https://api.adorable.io/avatars/'.$size.'/'.$data['uuid'].'.png';
    }

    /**
     * Get the size for the avatar, based on a given parameter. Provides a
     * default otherwise.
     *
     * @param  array  $data
     * @return int
     */
    private function size(array $data): int
    {
        if (isset($data['size'])) {
            return $data['size'];
        }

        return 200;
    }
}
