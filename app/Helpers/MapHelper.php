<?php

namespace App\Helpers;

use App\Models\Company\Place;

class MapHelper
{
    /**
     * Return the URL for a static image for the given place.
     *
     * @param Place $place
     * @param int $width
     * @param int $height
     * @param int $zoom
     *
     * @return string|null
     */
    public static function getStaticImage(Place $place, int $width, int $height, int $zoom = 7): ?string
    {
        if (! config('officelife.mapbox_api_key')) {
            return null;
        }

        if (! config('officelife.mapbox_api_username')) {
            return null;
        }

        $url = 'https://api.mapbox.com/styles/v1/';
        $url .= config('officelife.mapbox_api_username');
        $url .= '/ck335w8te1vzj1cn7aszafhm2/static/';
        $url .= $place->longitude.',';
        $url .= $place->latitude.',';
        $url .= $zoom.'/';
        $url .= $width.'x'.$height.'@2x';
        $url .= '?access_token='.config('officelife.mapbox_api_key');

        return $url;
    }
}
