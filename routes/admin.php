<?php

use App\Livewire\Admin\Cabanas;
use App\Livewire\Admin\Carrusel;
use App\Livewire\Admin\Contacto;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Especies;
use App\Livewire\Admin\Habitaciones;
use App\Livewire\Admin\Huespedes;
use App\Livewire\Admin\Lugares;
use App\Livewire\Admin\Reservas;
use App\Livewire\Admin\Temas;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/contacto', Contacto::class)->name('contacto');
    Route::get('/reservas', Reservas::class)->name('reservas');
    Route::get('/carrusel', Carrusel::class)->name('carrusel');
    Route::get('/huespedes', Huespedes::class)->name('huespedes');
    Route::get('/temas', Temas::class)->name('temas');
    Route::get('/cabanas', Cabanas::class)->name('cabanas');
    Route::get('/habitaciones', Habitaciones::class)->name('habitaciones');
    Route::get('/lugares', Lugares::class)->name('lugares');
    Route::get('/especies', Especies::class)->name('especies');
});
