<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirstAccessPasswordController;

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

    // Troca de senha no primeiro acesso (RF06)
    Route::get('/first-access', [FirstAccessPasswordController::class, 'edit'])->name('password.change');
    Route::put('/first-access', [FirstAccessPasswordController::class, 'update'])->name('password.change.update');
});

require __DIR__.'/auth.php';
