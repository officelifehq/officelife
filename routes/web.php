<?php

use Illuminate\Support\Facades\Route;

Route::get('signup', 'Auth\\RegisterController@index');
Route::post('signup', 'Auth\\RegisterController@store');
Route::get('login', 'Auth\\LoginController@index')->name('login');
Route::post('login', 'Auth\\LoginController@store');

Route::middleware(['auth'])->group(function () {
    Route::get('logout', 'Auth\\LoginController@logout');

    Route::get('dashboard', 'DashboardController@index');

    Route::resource('company', 'Company\\CompanyController')->only(['create', 'store']);

    // only available if user is in the right account
    Route::middleware(['company'])->prefix('{company}')->group(function () {
        Route::get('dashboard', 'Company\\CompanyController@index');

        // only available to administrator role
        Route::middleware(['administrator'])->group(function () {
            Route::get('account/audit', 'Company\\AuditController@index');
        });

        // only available to hr role
        Route::middleware(['hr'])->group(function () {
            Route::resource('account/employees', 'Account\\EmployeeController');
            Route::get('account/employees/{employee}/destroy', 'Account\\EmployeeController@destroy');
            Route::get('account/employees/{employee}/permissions', 'Account\\PermissionController@index');
            Route::post('account/employees/{employee}/permissions', 'Account\\PermissionController@store');
        });

        //Route::get('account/dummy', 'Account\\AccountController@dummy');
    });
});
