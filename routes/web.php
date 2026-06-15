<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirstAccessPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TripController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('users', UserController::class)->except(['show']);
    Route::patch('users/{user}/toggle-block', [UserController::class, 'toggleBlock'])->name('users.toggle-block');
    Route::resource('vehicles', VehicleController::class)->except(['show']);
    Route::resource('drivers', DriverController::class)->except(['show']);
    Route::post('/drivers/{driver}/photo', [\App\Http\Controllers\DriverController::class, 'updatePhoto'])->name('drivers.updatePhoto');
    Route::resource('trips', TripController::class)->except(['show']);

    // Troca de senha no primeiro acesso (RF06)
    Route::get('/first-access', [FirstAccessPasswordController::class, 'edit'])->name('password.change');
    Route::put('/first-access', [FirstAccessPasswordController::class, 'update'])->name('password.change.update');
});

require __DIR__.'/auth.php';
