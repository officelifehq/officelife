<?php

namespace App\Helpers;

use App\Models\Company\Employee;

class AvatarHelper
{
    /**
     * Get the avatar of the user, at the requested size if it exists.
     *
     * @var Employee
     * @var int
     * @return string|null
     */
    public static function getImage(Employee $employee, int $width = null): ?string
    {
        if (! $employee->avatar_file_id) {
            return 'https://ui-avatars.com/api/?name='.$employee->name;
        }

        if ($width) {
            $url = $employee->picture->cdn_url.'-/scale_crop/'.$width.'x'.$width.'/smart/';
        } else {
            $url = $employee->picture->cdn_url;
        }

        return $url;
    }
}
