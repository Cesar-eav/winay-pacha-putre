<?php

use App\Livewire\Admin\Cabanas;
use App\Livewire\Admin\Temas;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/temas', Temas::class)->name('temas');
    Route::get('/cabanas', Cabanas::class)->name('cabanas');
});
