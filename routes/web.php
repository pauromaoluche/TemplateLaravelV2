<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\IndexController;
use App\Livewire\Dashboard\Pages\Config\ConfigForm;
use App\Livewire\Dashboard\Pages\Config\ConfigList;
use App\Livewire\Dashboard\Pages\Index;
use App\Livewire\Dashboard\Pages\Institutional\InstitutionalForm;
use App\Livewire\Dashboard\Pages\Institutional\InstitutionalList;
use App\Livewire\Dashboard\Pages\User\UserForm;
use App\Livewire\Dashboard\Pages\User\UserList;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/login', [AuthController::class, 'index'])->name('login');

Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {
    Route::get('/', Index::class)->name('index');

    Route::get('/institucional', InstitutionalList::class)->name('institutional');
    Route::get('/institucional/adicionar', InstitutionalForm::class)->name('institutional.create');
    Route::get('/institucional/editar/{id}', InstitutionalForm::class)->name('institutional.edit');

    Route::get('/usuarios', UserList::class)->middleware('can:viewAny,App\Models\User')->name('user');
    Route::get('/usuarios/adicionar', UserForm::class)->middleware('can:create,App\Models\User')->name('user.create');
    Route::get('/usuario/editar/{id}', UserForm::class)->name('user.edit');

    Route::get('/configuracoes', ConfigList::class)->middleware('can:viewAny,App\Models\Config')->name('config');
    Route::get('/configuracoes/adicionar', ConfigForm::class)->middleware('can:create,App\Models\Config')->name('config.create');
    Route::get('/configuracoes/editar/{id}', ConfigForm::class)->middleware('can:update,App\Models\Config')->name('config.edit');
});
