<?php

use App\Http\Controllers\Api\EventApiController;

Route::get('/events', [EventApiController::class, 'index']);
Route::get('/events/{id}', [EventApiController::class, 'show']);
