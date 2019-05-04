<?php

use Illuminate\Support\Facades\Route;

Route::get('signup', 'Auth\\RegisterController@index');
Route::post('signup', 'Auth\\RegisterController@store');
Route::get('login', 'Auth\\LoginController@index')->name('login');
Route::post('login', 'Auth\\LoginController@store');

Route::get('invite/employee/{link}', 'Auth\\UserInvitationController@check');
Route::post('invite/employee/{link}/join', 'Auth\\UserInvitationController@join');
Route::post('invite/employee/{link}/accept', 'Auth\\UserInvitationController@accept');

Route::middleware(['auth'])->group(function () {
    Route::get('logout', 'Auth\\LoginController@logout');

    Route::get('home', 'HomeController@index')->name('home');
    Route::post('search/employees', 'HeaderSearchController@employees');
    Route::post('search/teams', 'HeaderSearchController@teams');

    Route::resource('company', 'Company\\CompanyController')->only(['create', 'store']);

    // only available if user is in the right account
    Route::middleware(['company'])->prefix('{company}')->group(function () {
        Route::get('dashboard', 'Company\\CompanyController@index');

        Route::prefix('employees')->group(function () {
            Route::get('{employee}', 'Company\\Employee\\EmployeeController@show');
            Route::post('{employee}/assignManager', 'Company\\Employee\\EmployeeController@assignManager');
            Route::post('{employee}/assignDirectReport', 'Company\\Employee\\EmployeeController@assignDirectReport');
            Route::post('{employee}/search/hierarchy', 'Company\\Employee\\EmployeeSearchController@hierarchy');
            Route::post('{employee}/unassignManager', 'Company\\Employee\\EmployeeController@unassignManager');
            Route::post('{employee}/unassignDirectReport', 'Company\\Employee\\EmployeeController@unassignDirectReport');

            Route::get('{employee}/logs', 'Company\\Employee\\EmployeeLogsController@index');

            Route::resource('{employee}/position', 'Company\\Employee\\Position\\EmployeePositionController')->only([
                'store', 'destroy',
            ]);

            Route::resource('{employee}/team', 'Company\\Employee\\Team\\EmployeeTeamController')->only([
                'store', 'destroy',
            ]);
        });

        Route::prefix('teams')->group(function () {
            Route::get('{team}', 'Company\\TeamController@show');
        });

        // only available to administrator role
        Route::middleware(['administrator'])->group(function () {
            Route::get('account/audit', 'Company\\Adminland\\AuditController@index');
            Route::get('account/dummy', 'Company\\Adminland\\DummyController@index');
        });

        // only available to hr role
        Route::middleware(['hr'])->group(function () {
            // adminland
            Route::get('account', 'Company\\Adminland\\AdminlandController@index');

            // employee management
            Route::resource('account/employees', 'Company\\Adminland\\EmployeeController');
            Route::get('account/employees/{employee}/destroy', 'Company\\Adminland\\EmployeeController@destroy');
            Route::get('account/employees/{employee}/permissions', 'Company\\Adminland\\PermissionController@index');
            Route::post('account/employees/{employee}/permissions', 'Company\\Adminland\\PermissionController@store');

            // team management
            Route::resource('account/teams', 'Company\\Adminland\\TeamController');
            Route::get('account/teams/{team}/destroy', 'Company\\Adminland\\TeamController@destroy');

            // position management
            Route::resource('account/positions', 'Company\\Adminland\\Position\\AdminPositionController');

            // flow management
            Route::resource('account/flows', 'Company\\Adminland\\AdminFlowController');
        });
    });
});
