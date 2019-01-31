<?php

Route::get('/signup', 'Auth\\RegisterController@index')->name('signup');
Route::post('/signup', 'Auth\\RegisterController@store')->name('signup.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', 'Auth\\LoginController@logout');

    Route::name('dashboard.')->group(function () {
        Route::get('/dashboard', 'DashboardController@index')->name('index');
    });

    Route::get('/account/dummy', 'Account\\AccountController@dummy');
});
