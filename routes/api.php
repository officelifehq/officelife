<?php


Route::group(['middleware' => ['auth:api']], function () {
    Route::get('me', 'API\\User\\ApiUserController@me');
    Route::apiResource('teams', 'API\\Account\\Team\\ApiTeamController');
});
