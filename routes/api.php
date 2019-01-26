<?php


Route::group(['middleware' => ['auth:api']], function () {
    Route::apiResource('users', 'API\\User\\ApiUserController');
    Route::apiResource('teams', 'API\\Account\\Team\\ApiTeamController');
});
