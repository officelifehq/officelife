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
    | Version of the application
    |--------------------------------------------------------------------------
    |
    | This value returns the current version of the application.
    |
    */
    'app_version' => is_file(__DIR__.'/version') ? file_get_contents(__DIR__.'/version') : (is_dir(__DIR__.'/../.git') ? trim(exec('git describe --abbrev=0 --tags')) : ''),

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
    | Used to display static maps. See https://docs.mapbox.com/help/how-mapbox-works/static-maps/
    | This should be the username used when creating the account.
    |
    */
    'mapbox_api_username' => env('MAPBOX_USERNAME', null),

    /*
    |--------------------------------------------------------------------------
    | Currency Layer API key
    |--------------------------------------------------------------------------
    |
    | Used to convert one currency to another.
    |
    */
    'currency_layer_api_key' => env('CURRENCY_LAYER_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Currency Layer plan
    |--------------------------------------------------------------------------
    |
    | The endpoint to use to convert a currency, for the paid plan.
    |
    */
    'currency_layer_plan' => env('CURRENCY_LAYER_PLAN', 'free'),

    /*
    |--------------------------------------------------------------------------
    | Currency Layer URL
    |--------------------------------------------------------------------------
    |
    | The endpoint to use to convert a currency, for the paid plan.
    |
    */
    'currency_layer_url_paid_plan' => env('CURRENCY_LAYER_URL', 'https://api.currencylayer.com/historical'),

    /*
    |--------------------------------------------------------------------------
    | Currency Layer URL
    |--------------------------------------------------------------------------
    |
    | The endpoint to use to convert a currency, for the free plan.
    |
    */
    'currency_layer_url_free_plan' => env('CURRENCY_LAYER_URL_FREE_PLAN', 'http://api.currencylayer.com/historical'),

    /*
    |--------------------------------------------------------------------------
    | URL of the documentation center
    |--------------------------------------------------------------------------
    |
    | This platform hosts the help documentation.
    |
    */
    'help_center_url' => 'https://docs.officelife.io/documentation/',

    /*
    |--------------------------------------------------------------------------
    | Mapping of the Help sections
    |--------------------------------------------------------------------------
    |
    | These are the links that are used in the UI to point to the right help
    | section.
    |
    */
    'help_links' => [
        'work_from_home' => 'manage/employee-management.html#work-from-home',
        'employee_expenses' => 'manage/employee-management.html#expenses',
        'adminland_expense_categories' => 'manage/employee-management.html#expense-categories',
        'manager_expenses' => 'manage/employee-management.html#expenses',
        'accoutant_expenses' => 'manage/employee-management.html#expenses',
        'accountants' => 'manage/employee-management.html#expenses',
        'manager_rate_manager' => 'manage/overview.html',
        'skills' => 'manage/employee-management.html#skills',
        'account_hardware_create' => 'introduction.html',
        'account_employee_delete' => 'manage/employee-management.html#deleting-an-employee',
        'account_employee_lock' => 'manage/employee-management.html#locking-an-employee',
        'team_recent_ship' => 'introduction.html',
        'team_recent_ship_create' => 'introduction.html',
        'account_general_company_name' => 'introduction.html',
        'account_general_currency' => 'introduction.html',
        'employee_hiring_date' => 'manage/employee-management.html#hiring-date',
        'employee_work_anniversaries' => 'manage/employee-management.html#work-anniversaries',
        'employee_statuses' => 'manage/employee-management.html#employee-statuses',
        'contract_renewal_dashboard' => 'manage/employee-management.html#what-happens-when-the-contract-renewal-date-is-due',
        'managing_external_employees' => 'manage/employee-management.html#managing-external-employees',
        'one_on_ones' => 'introduction.html',
        'project_decisions' => 'operate/project-management.html#project-decisions',
        'project_messages' => 'operate/project-management.html#project-messages',
        'project_tasks' => 'operate/project-management.html#project-tasks',
    ],
];
