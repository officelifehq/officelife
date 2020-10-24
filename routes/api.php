<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function () {
    Route::middleware(['company'])->prefix('{company}')->group(function () {
    });
});
