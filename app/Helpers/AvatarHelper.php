<?php

namespace App\Helpers;

use App\Models\Company\Employee;

class AvatarHelper
{
    /**
     * Get the avatar of the user, at the requested size if it exists.
     *
     * @var Employee
     * @return string|null
     */
    public static function getImage(Employee $employee): ?string
    {
        if (! $employee->avatar_file_id) {
            return 'https://ui-avatars.com/api/?name='.$employee->name;
        }

        return $employee->avatar;
    }
}
