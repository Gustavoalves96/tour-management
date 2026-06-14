<?php

use App\Http\Controllers\Api\TripController;
use Illuminate\Support\Facades\Route;

Route::get('/trips', [TripController::class, 'index']);
