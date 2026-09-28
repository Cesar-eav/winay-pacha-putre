<?php

use App\Livewire\Admin\Cabanas;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Especies;
use App\Livewire\Admin\Lugares;
use App\Livewire\Admin\Temas;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/temas', Temas::class)->name('temas');
    Route::get('/cabanas', Cabanas::class)->name('cabanas');
    Route::get('/lugares', Lugares::class)->name('lugares');
    Route::get('/especies', Especies::class)->name('especies');
});
