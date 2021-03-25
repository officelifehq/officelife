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
     * @return array|null
     */
    public static function getAvatar(Employee $employee, int $width = 64): ?array
    {
        if (! $employee->avatar_file_id) {
            return [
                'normal' => 'https://ui-avatars.com/api/?name='.urlencode($employee->name).'&size='.$width,
                'retina' => 'https://ui-avatars.com/api/?name='.urlencode($employee->name).'&size='.($width * 2),
            ];
        }

        if ($width) {
            $url = $employee->picture->cdn_url.'-/scale_crop/'.$width.'x'.$width.'/smart/';
            $url2x = $employee->picture->cdn_url.'-/scale_crop/'.($width * 2).'x'.($width * 2).'/smart/';
        } else {
            $url = $employee->picture->cdn_url;
            $url2x = $employee->picture->cdn_url;
        }

        return [
            'normal' => $url,
            'retina' => $url2x,
        ];
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
