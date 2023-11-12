<?php

use App\Http\Controllers\Api\V1\PatientApiController;
use App\Http\Controllers\Api\V1\TeamApiController;
use App\Http\Controllers\Api\V1\UserApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:api')->group(function () {
    Route::resource('user', UserApiController::class, [
        'only' => ['index']
    ]);

    Route::get('teams/{userId}', [TeamApiController::class, 'index']);
    Route::group(['mmiddleware' => [
        'team.verify'
    ]], function () {
        Route::resource('patients', PatientApiController::class, [
            'except' => ['create', 'edit']
        ]);
    });

});
