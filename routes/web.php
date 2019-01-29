<?php

Route::get('/signup', 'Auth\RegisterController@index')->name('signup');
Route::post('/signup', 'Auth\RegisterController@store')->name('signup.store');
