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
    | Demo mode
    |--------------------------------------------------------------------------
    |
    | The demo mode puts OfficeLife in a mode that is used to showcase what's
    | OfficeLife can do to people who don't know what it can do.
    | This mode is used on demo.officelife.io.
    |
    */
    'demo_mode' => env('DEMO_MODE', false),

    /*
    |--------------------------------------------------------------------------
    | Enable signups
    |--------------------------------------------------------------------------
    |
    | This setup enables new users to signup to this instance. If set to false,
    | new users won't be able to signup.
    |
    */
    'enable_signups' => env('ENABLE_SIGNUPS', true),

    /*
    |--------------------------------------------------------------------------
    | Email address of the system administrators of the instance
    |--------------------------------------------------------------------------
    |
    | This defines the email address of the administrators.
    |
    */
    'email_instance_administrator' => env('EMAIL_INSTANCE_ADMINISTRATOR'),

    /*
    |--------------------------------------------------------------------------
    | Version of the application
    |--------------------------------------------------------------------------
    |
    | This value returns the current version of the application.
    |
    */
    'app_version' => is_file(__DIR__.'/.version') ? file_get_contents(__DIR__.'/.version') : (is_dir(__DIR__.'/../.git') ? trim(exec('git --git-dir ' . base_path('.git') . ' describe --abbrev=0 --tags')) : ''),

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
    | Enable payment in the instance
    |--------------------------------------------------------------------------
    |
    | This is used to bill the customers of the OfficeLife instance.
    | You most likely don't need to touch this variable if you self-host.
    |
    */
    'enable_paid_plan' => env('ENABLE_PAID_PLAN', false),

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
    | Uploadcare public key
    |--------------------------------------------------------------------------
    |
    | The public key of Uploadcare, used to store uploaded documents.
    |
    */
    'uploadcare_public_key' => env('UPLOADCARE_PUBLIC_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | Uploadcare private key
    |--------------------------------------------------------------------------
    |
    | The private key of Uploadcare, used to store uploaded documents.
    |
    */
    'uploadcare_private_key' => env('UPLOADCARE_PRIVATE_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | Analytics API key
    |--------------------------------------------------------------------------
    |
    | We use Fathom for the analytics. https://usefathom.com
    |
    */
    'fathom_api_key' => env('FATHOM_API_KEY', null),

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
    | Mapping for default issue types
    |--------------------------------------------------------------------------
    |
    | Issue types are used in the project management domain.
    | Here we define the type of issue type, as well as the color of the icon.
    | When adding a new issue type here, don't forget to add the translation key
    | in the `en/account` lang file, so we know how to display the right information.
    |
    */
    'issue_types' => [
        'story' => '#2ca775',
        'bug' => '#ff5136',
        'epic' => '#5652b3',
        'task' => '#0080f8',
    ],

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
        'worklogs' => 'communicate/worklogs.html#work-logs',
        'employee_hiring_date' => 'manage/employee-management.html#hiring-date',
        'employee_work_anniversaries' => 'manage/employee-management.html#work-anniversaries',
        'employee_statuses' => 'manage/employee-management.html#employee-statuses',
        'skills' => 'manage/employee-management.html#skills',
        'account_employee_delete' => 'manage/employee-management.html#deleting-an-employee',
        'account_employee_lock' => 'manage/employee-management.html#locking-an-employee',
        'account_employee_permission' => 'manage/employee-management.html#changing-employee-role-permission',
        'contract_renewal_dashboard' => 'manage/employee-management.html#what-happens-when-the-contract-renewal-date-is-due',
        'managing_external_employees' => 'manage/employee-management.html#managing-external-employees',
        'import_employees' => 'manage/employee-management.html#importing-employees',
        'employee_morale' => 'manage/employee-management.html#employee-morale',
        'team_morale' => 'manage/team-management.html#team-morale',
        'manager_rate_manager' => 'grow/rate-your-manager.html',
        'hardware' => 'operate/hardware.html',
        'softwares' => 'operate/software.html',
        'team_recent_ship' => 'introduction.html',
        'team_recent_ship_create' => 'introduction.html',
        'account_general_company_name' => 'manage/company-management',
        'account_general_currency' => 'manage/company-management.html#currency',
        'account_general_logo' => 'manage/company-management.html#logo',
        'account_general_founded_date' => 'manage/company-management.html#defining-the-company-s-founded-date',
        'account_general_location' => 'manage/company-management.html#defining-the-company-s-main-location',
        'account_cancellation' => 'manage/company-management.html#account-cancellation',
        'one_on_ones' => 'grow/one-on-ones.html#overview',
        'project' => 'operate/project-management.html#overview',
        'project_decisions' => 'operate/project-management.html#project-decisions',
        'project_messages' => 'operate/project-management.html#project-messages',
        'project_tasks' => 'operate/project-management.html#project-tasks',
        'project_files' => 'operate/project-management.html#project-files',
        'ecoffee' => 'grow/e-coffee.html#overview',
        'questions' => 'communicate/get-to-know-your-colleagues.html',
        'time_tracking' => 'operate/time-tracking.html',
        'billing' => 'manage/company-management.html#account-billing',
        'wiki' => 'communicate/wiki.html',
        'recruitment_template' => 'recruit/applicant-tracking-system.html#the-recruiting-stages-and-templates',
        'ask_me_anything' => 'communicate/ask-me-anything.html',
        'discipline' => 'grow/discipline.html',
    ],
];
