<?php

use Illuminate\Http\Request;

Route::group(['middleware' => ['auth:api']], function () {
    Route::apiResource('users', 'API\\User\\ApiUserController');
});
