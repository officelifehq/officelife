<?php

namespace App\Helpers;

use App\Models\Company\File;
use App\Models\Company\Employee;

class ImageHelper
{
    /**
     * Get the avatar of the user, at the requested size if it exists.
     *
     * @param Employee $employee
     * @param int $width
     * @return string|null
     */
    public static function getAvatar(Employee $employee, int $width = null): ?string
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

    /**
     * Get the URL of an image.
     *
     * @param File $file
     * @param int $width
     * @param int $height
     * @return string|null
     */
    public static function getImage(File $file, int $width = null, int $height = null): ?string
    {
        return $file->cdn_url.'-/preview/'.$width.'x'.$height.'/';
    }
}
