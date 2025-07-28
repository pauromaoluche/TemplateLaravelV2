<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('index');

Route::get('/login', [AuthController::class, 'index'])->name('login');
