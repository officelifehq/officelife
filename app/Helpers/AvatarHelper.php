<?php

namespace App\Helpers;

use App\Models\Company\Employee;

class AvatarHelper
{
    /**
     * Returns the url of the avatar of the right size.
     *
     * @param Employee $employee
     * @param int $width
     * @return string|null
     */
    public static function getURL(Employee $employee, int $width = 512): ?string
    {
        if (! $employee->avatar) {
            return null;
        }
    }
}
