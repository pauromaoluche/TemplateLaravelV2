<?php

use App\Http\Controllers\Dashboard\IndexController as DashboardIndexController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/login', [AuthController::class, 'index'])->name('login');

Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardIndexController::class, 'index'])->name('index');
});
