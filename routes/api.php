<?php

use App\Http\Controllers\Api\ContactsApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->name('api.')->group(function () {
    Route::get('/user', fn (Request $r) => $r->user())->name('user');

    Route::apiResource('contacts', ContactsApiController::class);
});
