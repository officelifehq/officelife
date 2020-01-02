<?php

use Illuminate\Support\Facades\Route;

// auth
Route::get('signup', 'Auth\\RegisterController@index')->name('signup');
Route::post('signup', 'Auth\\RegisterController@store')->name('signup.attempt');
Route::get('login', 'Auth\\LoginController@index')->name('login');
Route::post('login', 'Auth\\LoginController@store')->name('login.attempt');

Route::get('invite/employee/{link}', 'Auth\\UserInvitationController@check');
Route::post('invite/employee/{link}/join', 'Auth\\UserInvitationController@join');
Route::post('invite/employee/{link}/accept', 'Auth\\UserInvitationController@accept');

Route::middleware(['auth'])->group(function () {
    Route::get('logout', 'Auth\\LoginController@logout');

    Route::get('home', 'HomeController@index')->name('home');
    Route::post('search/employees', 'HeaderSearchController@employees');
    Route::post('search/teams', 'HeaderSearchController@teams');

    Route::resource('company', 'Company\\CompanyController')->only(['create', 'store']);

    Route::post('notifications/read', 'User\\Notification\\MarkNotificationAsReadController@store');

    // only available if user is in the right account
    Route::middleware(['company'])->prefix('{company}')->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::get('', 'Company\\Dashboard\\DashboardController@index')->name('dashboard');
            Route::get('me', 'Company\\Dashboard\\DashboardMeController@index')->name('dashboard.me');
            Route::get('company', 'Company\\Dashboard\\DashboardCompanyController@index')->name('dashboard.company');
            Route::get('hr', 'Company\\Dashboard\\DashboardHRController@index')->name('dashboard.hr');

            Route::get('team', 'Company\\Dashboard\\DashboardTeamController@index')->name('dashboard.team');
            Route::get('team/{team}', 'Company\\Dashboard\\DashboardTeamController@index');
            Route::get('team/{team}/{date}', 'Company\\Dashboard\\DashboardTeamController@worklogDetails');

            Route::post('worklog', 'Company\\Dashboard\\DashboardWorklogController@store');
            Route::post('morale', 'Company\\Dashboard\\DashboardMoraleController@store');
        });

        Route::prefix('employees')->group(function () {
            Route::get('', 'Company\\Employee\\EmployeeController@index');
            Route::get('{employee}', 'Company\\Employee\\EmployeeController@show');
            Route::post('{employee}/assignManager', 'Company\\Employee\\EmployeeController@assignManager');
            Route::post('{employee}/assignDirectReport', 'Company\\Employee\\EmployeeController@assignDirectReport');
            Route::post('{employee}/search/hierarchy', 'Company\\Employee\\EmployeeSearchController@hierarchy');
            Route::post('{employee}/unassignManager', 'Company\\Employee\\EmployeeController@unassignManager');
            Route::post('{employee}/unassignDirectReport', 'Company\\Employee\\EmployeeController@unassignDirectReport');

            Route::get('{employee}/logs', 'Company\\Employee\\EmployeeLogsController@index');
            Route::get('{employee}/edit', 'Company\\Employee\\EmployeeEditController@show');
            Route::post('{employee}/update', 'Company\\Employee\\EmployeeEditController@update');

            Route::resource('{employee}/position', 'Company\\Employee\\EmployeePositionController')->only([
                'store', 'destroy',
            ]);

            Route::resource('{employee}/team', 'Company\\Employee\\EmployeeTeamController')->only([
                'store', 'destroy',
            ]);

            Route::resource('{employee}/employeestatuses', 'Company\\Employee\\EmployeeStatusController')->only([
                'store', 'destroy',
            ]);

            Route::resource('{employee}/pronoun', 'Company\\Employee\\EmployeePronounController')->only([
                'store', 'destroy',
            ]);

            Route::resource('{employee}/description', 'Company\\Employee\\EmployeeDescriptionController')->only([
                'store', 'destroy',
            ]);
        });

        Route::prefix('teams')->group(function () {
            Route::get('', 'Company\\Team\\TeamController@index');
            Route::get('{team}', 'Company\\Team\\TeamController@show');
            Route::resource('{team}/news', 'Company\\Team\\TeamNewsController');
        });

        // only available to administrator role
        Route::middleware(['administrator'])->group(function () {
            Route::get('account/audit', 'Company\\Adminland\\AdminAuditController@index');
            Route::get('account/dummy', 'Company\\Adminland\\DummyController@index');
        });

        // only available to hr role
        Route::middleware(['hr'])->group(function () {
            // adminland
            Route::get('account', 'Company\\Adminland\\AdminlandController@index');

            // employee management
            Route::resource('account/employees', 'Company\\Adminland\\AdminEmployeeController');
            Route::get('account/employees/{employee}/permissions', 'Company\\Adminland\\PermissionController@index');
            Route::post('account/employees/{employee}/permissions', 'Company\\Adminland\\PermissionController@store');

            // team management
            Route::resource('account/teams', 'Company\\Adminland\\AdminTeamController');

            // position management
            Route::resource('account/positions', 'Company\\Adminland\\AdminPositionController');

            // flow management
            Route::resource('account/flows', 'Company\\Adminland\\AdminFlowController');

            // employee statuses
            Route::resource('account/employeestatuses', 'Company\\Adminland\\AdminEmployeeStatusController');

            // company news
            Route::resource('account/news', 'Company\\Adminland\\AdminCompanyNewsController');

            // pto policies
            Route::resource('account/ptopolicies', 'Company\\Adminland\\AdminPTOPoliciesController');
            Route::get('account/ptopolicies/{ptopolicy}/getHolidays', 'Company\\Adminland\\AdminPTOPoliciesController@getHolidays');
        });
    });
});
