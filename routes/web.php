<?php

Route::get('signup', 'Auth\\RegisterController@index')->name('signup');
Route::post('signup', 'Auth\\RegisterController@store')->name('signup.store');
Route::get('login', 'Auth\\LoginController@index')->name('login.index');
Route::post('login', 'Auth\\LoginController@store')->name('login.store');

Route::middleware(['auth'])->group(function () {
    Route::get('logout', 'Auth\\LoginController@logout');

    Route::middleware(['account'])->prefix('{account}')->group(function () {
        Route::name('dashboard.')->group(function () {
            Route::get('dashboard', 'DashboardController@index')->name('index');
        });

        Route::name('administrator.')->group(function () {
            Route::get('account/audit', 'Account\\AuditController@index')->name('audit');
        });

        Route::get('account/dummy', 'Account\\AccountController@dummy');
    });
});
