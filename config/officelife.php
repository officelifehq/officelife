<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Name of the company
    |--------------------------------------------------------------------------
    |
    | This defines the name of the site.
    |
    */
    'name' => 'OfficeLife',

    /*
    |--------------------------------------------------------------------------
    | Permission levels
    |--------------------------------------------------------------------------
    |
    | This value defines the different user permission levels.
    |
    */
    'permission_level' => [
        'administrator' => 100,
        'hr' => 200,
        'user' => 300,
    ],

    /*
    |--------------------------------------------------------------------------
    | API key for geolocation service.
    |--------------------------------------------------------------------------
    |
    | We use LocationIQ (https://locationiq.com/) to translate addresses to
    | latitude/longitude coordinates. We could use Google instead but we don't
    | want to give anything to Google, ever.
    | LocationIQ offers 10,000 free requests per day.
    |
    */
    'location_iq_api_key' => env('LOCATION_IQ_API_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | Locationiq API Url
    |--------------------------------------------------------------------------
    |
    | Url to call LocationHQ api. See https://locationiq.com/docs
    |
    */
    'location_iq_url' => env('LOCATION_IQ_URL', 'https://us1.locationiq.com/v1/'),

    /*
    |--------------------------------------------------------------------------
    | Mapbox API key
    |--------------------------------------------------------------------------
    |
    | Used to display static maps. See https://docs.mapbox.com/help/how-mapbox-works/static-maps/
    |
    */
    'mapbox_api_key' => env('MAPBOX_API_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | Mapbox username
    |--------------------------------------------------------------------------
    |
    | Necessary for the Mapbox API to work.
    |
    */
    'mapbox_username' => env('MAPBOX_USERNAME', null),
];
