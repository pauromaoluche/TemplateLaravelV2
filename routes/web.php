<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\IndexController;
use App\Livewire\Dashboard\Pages\Index;
use App\Livewire\Dashboard\Pages\Institutional\InstitutionalForm;
use App\Livewire\Dashboard\Pages\Institutional\InstitutionalList;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/login', [AuthController::class, 'index'])->name('login');

Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {
    // Route::get('/', [DashboardIndexController::class, 'index'])->name('index');
    Route::get('/', Index::class)->name('index');

    Route::get('/institucional', InstitutionalList::class)->name('institutional');
    Route::get('/institucional/Adicionar', InstitutionalForm::class)->name('institutional.create');
    Route::get('/institucional/editar/{id}', InstitutionalForm::class)->name('institutional.edit');
});
