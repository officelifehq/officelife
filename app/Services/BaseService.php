<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User\User;
use Illuminate\Support\Facades\Validator;

abstract class BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Validate an array against a set of rules.
     *
     * @param array $data
     * @return bool
     */
    public function validate(array $data) : bool
    {
        $validator = Validator::make($data, $this->rules())
            ->validate();

        return true;
    }

    /**
     * Checks if the user has the permission to do the action.
     *
     * @param int $userId
     * @param string $requiredPermissionLevel
     * @return bool
     */
    public function validatePermissions(int $userId, string $requiredPermissionLevel) : bool
    {
        $user = User::find($userId);

        return config('homas.authorizations.'.$requiredPermissionLevel) >= $user->permission_level;
    }

    /**
     * Checks if the value is empty or null.
     *
     * @param mixed $data
     * @param mixed $index
     * @return mixed
     */
    public function nullOrValue($data, $index)
    {
        if (empty($data[$index])) {
            return;
        }

        return $data[$index] == '' ? null : $data[$index];
    }

    /**
     * Checks if the value is empty or null and returns a date from a string.
     *
     * @param mixed $data
     * @param mixed $index
     * @return mixed
     */
    public function nullOrDate($data, $index)
    {
        if (empty($data[$index])) {
            return;
        }

        return $data[$index] == '' ? null : Carbon::parse($data[$index]);
    }
}
