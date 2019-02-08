<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('me', 'API\\User\\ApiUserController@me');

    Route::middleware(['company'])->prefix('{company}')->group(function () {
        Route::apiResource('teams', 'API\\Company\\Team\\ApiTeamController');
    });
});
